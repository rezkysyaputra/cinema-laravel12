# Panduan Deployment ke AnyMhost

Dokumen ini berisi panduan untuk melakukan deployment aplikasi Laravel ke AnyMhost menggunakan GitHub Actions.

## Persiapan

1. Buat akun di AnyMhost dan dapatkan informasi berikut:
   - Host SSH
   - Port SSH
   - Username SSH
   - Path deployment (contoh: /home/username/public_html)

2. Generate SSH key pair:
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
   - Simpan private key untuk digunakan di GitHub Secrets
   - Upload public key ke AnyMhost melalui panel kontrol

## Konfigurasi GitHub Secrets

Tambahkan secrets berikut di repository GitHub (Settings > Secrets and variables > Actions):

1. `SSH_HOST`: Hostname AnyMhost (contoh: srv1.anymhost.com)
2. `SSH_PORT`: Port SSH AnyMhost (biasanya 22)
3. `SSH_USER`: Username SSH AnyMhost
4. `SSH_PRIVATE_KEY`: Isi dari private key SSH yang telah digenerate
5. `DEPLOY_PATH`: Path lengkap ke direktori deployment (contoh: /home/username/public_html)

## Konfigurasi Environment

1. Salin file `.env.example` menjadi `.env` di server AnyMhost
2. Sesuaikan konfigurasi database dan environment lainnya
3. Pastikan direktori berikut memiliki permission yang benar:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## Deployment Manual

Jika perlu melakukan deployment manual:

1. SSH ke server AnyMhost
2. Masuk ke direktori project
3. Jalankan perintah berikut:
   ```bash
   git pull origin main
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize:clear
   php artisan permission:cache-reset
   ```

## Troubleshooting

1. Jika terjadi masalah permission:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data .
   ```

2. Jika perlu membersihkan cache:
   ```bash
   php artisan optimize:clear
   ```

3. Jika ada masalah dengan database:
   ```bash
   php artisan migrate:status
   php artisan migrate:fresh --seed --force
   ```