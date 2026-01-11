📋 LOGIN CREDENTIALS & SETUP GUIDE
═══════════════════════════════════════════════════════════════

🔐 DEFAULT TEST USER CREDENTIALS:

Email:    test@example.com
Password: password

═══════════════════════════════════════════════════════════════

🚀 QUICK START:

1. Mulai Server
   ┌─────────────────────────────────────────┐
   │ cd c:\laragon\www\SistemVSB\SistemVSB   │
   │ php artisan migrate --seed             │
   │ php artisan serve                      │
   └─────────────────────────────────────────┘

2. Buka Browser
   └─ http://localhost:8000

3. Klik "Login"
   └─ Email: test@example.com
   └─ Password: password

4. Click "Login" Button
   └─ Dashboard should load

═══════════════════════════════════════════════════════════════

✅ IF DATABASE NOT SEEDED YET:

Run this command:

  php artisan migrate --seed

This will:
  ✅ Create all database tables
  ✅ Create test user: test@example.com / password
  ✅ Create sample data (if seeders exist)

═══════════════════════════════════════════════════════════════

🔑 LOGIN FLOW:

1. App starts → redirect ke /clients
2. Not logged in → redirect ke /login
3. Enter credentials:
   - Email: test@example.com
   - Password: password
4. Click "Login"
5. ✅ Redirected to dashboard
6. ✅ FCM token will auto-save

═══════════════════════════════════════════════════════════════

🧪 TESTING FIREBASE AFTER LOGIN:

1. Login dengan test account
2. F12 → Console tab
3. Look for logs:
   ✅ "✓ Firebase initialized successfully"
   ✅ "✓ Service Worker registered"
   ✅ "✓ FCM Token saved: ..."

4. Browser akan minta permission:
   "Allow notifications?"
   → Click "Allow"

5. Verifikasi di database:
   php artisan tinker
   >>> App\Models\Client::pluck('fcm_token')

═══════════════════════════════════════════════════════════════

📱 TEST NOTIFICATION:

Setelah login & FCM token tersimpan:

1. Go to clients list: /clients
2. Click on any client
3. Look for button: "📲 Send Test Notification"
4. Click it
5. ✅ Should receive notification in browser

═══════════════════════════════════════════════════════════════

❌ IF LOGIN FAILS:

Problem 1: "Invalid credentials"
└─ Check: Email/password sudah benar?
└─ Verify: Database seeded? (php artisan migrate --seed)

Problem 2: "No users in database"
└─ Run: php artisan migrate:fresh --seed
└─ This will reset & reseed database

Problem 3: Can't access http://localhost:8000
└─ Check: Server running? (php artisan serve)
└─ Check: Port 8000 not blocked
└─ Try: http://127.0.0.1:8000 instead

═══════════════════════════════════════════════════════════════

👤 CREATE NEW USER (via Register):

1. At login page, click "Register"
2. Fill form:
   - Name: Your Name
   - Email: your@email.com
   - Password: min 8 chars
   - Confirm Password: same as above
3. Click "Register"
4. ✅ Auto-login & go to dashboard

═══════════════════════════════════════════════════════════════

🛠️ RESET TO DEFAULT STATE:

If you want to reset everything:

  php artisan migrate:fresh --seed

This will:
  ❌ DROP all tables
  ✅ Create fresh tables
  ✅ Seed test user: test@example.com / password
  ✅ Create sample clients (if seeder exists)

⚠️ WARNING: This deletes ALL data!

═══════════════════════════════════════════════════════════════

💾 DATABASE STRUCTURE:

Users Table:
├─ id (primary key)
├─ name
├─ email (unique)
├─ password (hashed)
├─ email_verified_at
├─ remember_token
└─ timestamps

Clients Table:
├─ id (primary key)
├─ name
├─ website
├─ email
├─ expiry_date
├─ notes
├─ fcm_token ← Saved here after login & notification
└─ timestamps

═══════════════════════════════════════════════════════════════

🔍 VERIFY SETUP:

Command to check everything:

1. Check if server running:
   http://localhost:8000

2. Check if can access login:
   http://localhost:8000/login

3. Check database connection:
   php artisan tinker
   >>> DB::table('users')->count()
   (should return 1 or more)

4. Check test user exists:
   >>> App\Models\User::first()
   (should show test@example.com)

5. Check Firebase config:
   >>> env('FIREBASE_VAPID_KEY')
   (should show key, not empty)

═══════════════════════════════════════════════════════════════

📊 AFTER SUCCESSFUL LOGIN:

Expected behavior:
✅ Redirected to /clients dashboard
✅ See list of clients (or empty if no clients yet)
✅ Can create new client: /clients/create
✅ Can view client detail: /clients/{id}
✅ Browser console shows Firebase logs
✅ FCM token saved to database

═══════════════════════════════════════════════════════════════

🎯 NEXT STEPS:

1. Run migration with seed:
   php artisan migrate --seed

2. Start server:
   php artisan serve

3. Go to: http://localhost:8000

4. Login: test@example.com / password

5. Check console (F12) for Firebase logs

6. Allow notifications

7. Test notification from client page

═══════════════════════════════════════════════════════════════

✨ EVERYTHING IS READY! 

Just login with the default credentials above and test! 🎉

═══════════════════════════════════════════════════════════════
