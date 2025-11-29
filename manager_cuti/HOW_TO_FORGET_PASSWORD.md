# 🔐 Panduan Lengkap: Cara Melakukan Forget Password di CUTI-IN

## 📋 Langkah-Langkah Forget Password

### **Langkah 1: Akses Halaman Forget Password**

**Cara 1: Dari Halaman Login**
1. Buka halaman login: `http://localhost:8000/login`
2. Klik link **"Lupa password?"** di pojok kanan atas form login

**Cara 2: Langsung**
- Buka URL: `http://localhost:8000/forgot-password`

---

### **Langkah 2: Masukkan Email**

1. Di halaman "Forgot Password", masukkan **email yang terdaftar** di sistem
2. Klik tombol **"Email Password Reset Link"**

---

### **Langkah 3: Mendapatkan Reset Link**

⚠️ **PENTING**: Karena aplikasi menggunakan mail driver `log` (untuk development), email **TIDAK benar-benar dikirim**. Reset link tersimpan di log file.

#### **Cara Melihat Reset Link di Log File:**

1. **Buka file log:**
   ```
   storage/logs/laravel.log
   ```

2. **Cari baris terakhir** yang berisi:
   - `reset-password`
   - `password reset`
   - `Reset Password Notification`

3. **Copy URL reset link** yang muncul di log, contoh:
   ```
   http://localhost:8000/reset-password/abc123def456?email=user@example.com
   ```

#### **Cara Cepat dengan Terminal:**

```bash
# Windows PowerShell
Get-Content storage\logs\laravel.log -Tail 50 | Select-String "reset-password"

# Atau buka file dan cari dengan Ctrl+F: "reset-password"
```
