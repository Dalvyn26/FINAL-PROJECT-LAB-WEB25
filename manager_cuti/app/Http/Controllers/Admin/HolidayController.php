<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display a listing of the holidays.
     */
    public function index(Request $request)
    {
        $query = Holiday::query();

        // Apply search filter
        $search = $request->query('search');
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Apply filter by national holiday
        $filter = $request->query('filter');
        if ($filter === 'national') {
            $query->where('is_national_holiday', true);
        } elseif ($filter === 'manual') {
            $query->where('is_national_holiday', false);
        }

        // Apply sorting
        $sort = $request->query('sort', 'holiday_date_asc');
        switch ($sort) {
            case 'holiday_date_asc':
                $query->orderBy('holiday_date', 'asc');
                break;
            case 'holiday_date_desc':
                $query->orderBy('holiday_date', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('holiday_date', 'asc');
                break;
        }

        $holidays = $query->paginate(15)->withQueryString();

        return view('admin.holidays.index', compact('holidays', 'search', 'sort', 'filter'));
    }

    /**
     * Show the form for creating a new holiday.
     */
    public function create()
    {
        return view('admin.holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'description' => 'nullable|string',
        ]);

        Holiday::create([
            'title' => $request->title,
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
            'is_national_holiday' => false, // Manual input is not national holiday
        ]);

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Hari libur berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified holiday.
     */
    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $holiday->id,
            'description' => 'nullable|string',
        ]);

        $holiday->update([
            'title' => $request->title,
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Hari libur berhasil diperbarui');
    }

    /**
     * Remove the specified holiday from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Hari libur berhasil dihapus');
    }

    /**
     * Remove multiple holidays from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->ids, true);
        
        if (!is_array($ids) || empty($ids)) {
            return redirect()->route('admin.holidays.index')
                ->withErrors(['error' => 'No holidays selected for deletion']);
        }

        $count = Holiday::whereIn('id', $ids)->delete();

        return redirect()->route('admin.holidays.index')
            ->with('success', "Berhasil menghapus {$count} hari libur");
    }

    /**
     * Fetch and sync holidays from Google Calendar (Indonesian National Holidays).
     */
    public function fetchGoogleHolidays()
    {
        try {
            $url = 'https://calendar.google.com/calendar/ical/id.indonesian%23holiday%40group.v.calendar.google.com/public/basic.ics';
            
            // Fetch the iCal data
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                return redirect()->back()
                    ->withErrors(['error' => 'Gagal mengambil data dari Google Calendar']);
            }

            $icalContent = $response->body();
            
            // Parse iCal content
            $holidays = $this->parseICal($icalContent);
            
            // Filter only this year and next year
            $currentYear = Carbon::now()->year;
            $nextYear = $currentYear + 1;
            
            $filteredHolidays = array_filter($holidays, function ($holiday) use ($currentYear, $nextYear) {
                $year = Carbon::parse($holiday['date'])->year;
                return $year == $currentYear || $year == $nextYear;
            });

            // Save to database using updateOrCreate
            $count = 0;
            foreach ($filteredHolidays as $holiday) {
                Holiday::updateOrCreate(
                    ['holiday_date' => $holiday['date']],
                    [
                        'title' => $holiday['title'],
                        'description' => $holiday['description'] ?? null,
                        'is_national_holiday' => true,
                    ]
                );
                $count++;
            }

            return redirect()->route('admin.holidays.index')
                ->with('success', "Berhasil sinkronisasi {$count} hari libur nasional");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Parse iCal content and extract holidays.
     */
    private function parseICal(string $icalContent): array
    {
        $holidays = [];
        $lines = explode("\r\n", $icalContent); // Try CRLF first
        if (count($lines) === 1) {
            $lines = explode("\n", $icalContent); // Fallback to LF
        }
        
        // Normalize line continuations (lines starting with space/tab are continuations)
        $normalizedLines = [];
        $currentLine = '';
        foreach ($lines as $line) {
            // Check if line is a continuation (starts with space or tab)
            if (preg_match('/^[ \t]/', $line)) {
                // This is a continuation line - append to current line (remove leading space/tab)
                $currentLine .= ltrim($line, " \t");
            } else {
                // New line - save previous line if exists
                if ($currentLine !== '') {
                    $normalizedLines[] = $currentLine;
                }
                $currentLine = trim($line);
            }
        }
        // Don't forget the last line
        if ($currentLine !== '') {
            $normalizedLines[] = $currentLine;
        }
        
        $currentEvent = null;
        
        foreach ($normalizedLines as $line) {
            // Reset event when new VEVENT starts
            if (preg_match('/^BEGIN:VEVENT$/', $line)) {
                $currentEvent = null;
                continue;
            }
            
            // Check for DTSTART;VALUE=DATE or DTSTART:YYYYMMDD
            if (preg_match('/^DTSTART(?:;VALUE=DATE)?:(\d{8})$/', $line, $matches)) {
                $dateStr = $matches[1];
                // Format: YYYYMMDD
                try {
                    $date = Carbon::createFromFormat('Ymd', $dateStr)->format('Y-m-d');
                    if (!$currentEvent) {
                        $currentEvent = [];
                    }
                    $currentEvent['date'] = $date;
                } catch (\Exception $e) {
                    // Skip invalid date
                    continue;
                }
            }
            
            // Check for SUMMARY (holiday title)
            if (preg_match('/^SUMMARY:(.+)$/', $line, $matches)) {
                if (!$currentEvent) {
                    $currentEvent = [];
                }
                $currentEvent['title'] = trim($matches[1]);
            }
            
            // Check for DESCRIPTION
            if (preg_match('/^DESCRIPTION:(.+)$/', $line, $matches)) {
                if (!$currentEvent) {
                    $currentEvent = [];
                }
                // Remove \n from description if present
                $description = trim($matches[1]);
                $description = str_replace('\\n', ' ', $description);
                $currentEvent['description'] = $description;
            }
            
            // End of event - save it
            if (preg_match('/^END:VEVENT$/', $line)) {
                if ($currentEvent && isset($currentEvent['date']) && isset($currentEvent['title'])) {
                    $holidays[] = $currentEvent;
                }
                $currentEvent = null;
            }
        }
        
        // Handle last event if exists (shouldn't happen with proper iCal, but just in case)
        if ($currentEvent && isset($currentEvent['date']) && isset($currentEvent['title'])) {
            $holidays[] = $currentEvent;
        }
        
        return $holidays;
    }
}
