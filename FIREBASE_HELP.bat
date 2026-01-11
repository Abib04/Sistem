@echo off
REM Firebase Setup Helper - Windows Batch Script
REM Usage: Run this file di project folder untuk melihat helpful commands

cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║     🔥 FIREBASE SETUP - HELPFUL COMMANDS & TIPS 🔥          ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

echo 📋 LANGKAH-LANGKAH SETELAH DOWNLOAD CREDENTIALS:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo 1️⃣  VERIFY Firebase Config di .env
echo    Command:
echo    findstr "FIREBASE" .env
echo.

echo 2️⃣  CLEAR CACHE
echo    php artisan cache:clear
echo    php artisan config:clear
echo.

echo 3️⃣  RUN MIGRATIONS
echo    php artisan migrate
echo.

echo 4️⃣  TEST Firebase Tinker
echo    php artisan tinker
echo    ^> env('FIREBASE_API_KEY')
echo    ^> env('FIREBASE_VAPID_KEY')
echo.

echo 5️⃣  START DEVELOPMENT SERVER
echo    php artisan serve
echo.

echo 6️⃣  LOGIN & TEST
echo    - Buka http://localhost:8000
echo    - Login dengan akun Anda
echo    - Buka F12 Console
echo    - Check logs Firebase
echo.

echo 7️⃣  VERIFY DATABASE
echo    php artisan tinker
echo    ^> App\Models\Client::pluck('fcm_token')
echo.

echo.
echo 🔧 TROUBLESHOOTING COMMANDS:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo ❌ Firebase Config tidak terload?
echo    php artisan config:cache --env=local
echo    php artisan cache:clear
echo.

echo ❌ Kolom fcm_token tidak ada di database?
echo    php artisan migrate:refresh
echo    php artisan migrate
echo.

echo ❌ Ingin reset semua & start fresh?
echo    php artisan migrate:reset
echo    php artisan migrate:fresh
echo.

echo ❌ FCM Token API error?
echo    - Check .env FIREBASE_VAPID_KEY
echo    - Check .env FIREBASE_API_KEY
echo    - Clear browser cache (Ctrl+Shift+Delete)
echo    - Hard refresh (Ctrl+Shift+R)
echo.

echo ❌ Service Worker error?
echo    - Check file: public\firebase-messaging-sw.js exists
echo    - Check browser console F12 untuk error details
echo.

echo.
echo 📊 QUICK VERIFICATION:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo Check 1: Environment Variables
findstr /B "FIREBASE_" .env | findstr /V "^REM"
echo.

echo Check 2: Database Migration Status
echo    (Run: php artisan migrate:status)
echo.

echo Check 3: Routes
echo    (Run: php artisan route:list | findstr "fcm")
echo.

echo.
echo 🎯 FINAL CHECKLIST:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo ☐ 1. Firebase credentials JSON downloaded & saved?
echo        Path: storage\firebase-credentials.json
echo.

echo ☐ 2. VAPID Key di .env sudah updated?
echo        FIREBASE_VAPID_KEY=BMi_xxxxx...
echo.

echo ☐ 3. Database migrated?
echo        php artisan migrate
echo.

echo ☐ 4. All config variables loaded?
echo        php artisan tinker
echo        ^> env('FIREBASE_API_KEY')
echo.

echo ☐ 5. Server running & can access http://localhost:8000?
echo        php artisan serve
echo.

echo ☐ 6. Logged in & FCM token saved to database?
echo        Check: SELECT fcm_token FROM clients;
echo.

echo ☐ 7. Browser console shows Firebase logs?
echo        F12 ^ Console ^ Check for ✓ messages
echo.

echo.
echo 🚀 QUICK START (Copy-paste one by one):
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo php artisan cache:clear
echo php artisan config:clear
echo php artisan migrate
echo php artisan serve
echo.

echo.
echo 📱 AFTER SETUP - How to Send Notifications:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo 1. Via UI:
echo    - Login to app
echo    - Go to Client detail page
echo    - Click "📲 Send Test Notification" button
echo.

echo 2. Via Tinker (Manual):
echo    php artisan tinker
echo    ^> $client = App\Models\Client::first()
echo    ^> app('App\Services\FirebaseService')->sendNotification(
echo       $client->fcm_token, 'Test Title', 'Test Message'
echo    )
echo.

echo 3. Via API:
echo    POST /save-fcm-token
echo    POST /clients/{id}/test-notification
echo.

echo.
echo 📞 NEED HELP?
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo Check these files:
echo  - FIREBASE_SETUP.md (Original guide)
echo  - FIREBASE_SETUP_CHECKLIST.md (Quick checklist)
echo  - FIREBASE_VERIFICATION.md (Detailed verification)
echo.

echo.
echo ✅ Setup Complete! Ready untuk testing after credentials added.
echo.
pause
