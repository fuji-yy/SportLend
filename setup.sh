#!/bin/bash
# Sipinjam Installation & Setup Script

echo "═══════════════════════════════════════════════════════════"
echo "🎯 Sipinjam - Tool Rental Management System"
echo "═══════════════════════════════════════════════════════════"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from sipinjam directory."
    exit 1
fi

echo "📦 Installing PHP dependencies..."
composer install --no-interaction
if [ $? -ne 0 ]; then
    echo "❌ Composer install failed"
    exit 1
fi
echo "✅ PHP dependencies installed"
echo ""

echo "📦 Installing Node.js dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "❌ NPM install failed"
    exit 1
fi
echo "✅ Node dependencies installed"
echo ""

echo "🏗️ Building frontend assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ NPM build failed"
    exit 1
fi
echo "✅ Frontend assets built"
echo ""

echo "🗄️ Running database migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "❌ Migration failed"
    exit 1
fi
echo "✅ Database migrations completed"
echo ""

echo "🌱 Seeding database with demo data..."
php artisan db:seed --force
if [ $? -ne 0 ]; then
    echo "❌ Database seeding failed"
    exit 1
fi
echo "✅ Database seeded"
echo ""

echo "═══════════════════════════════════════════════════════════"
echo "✅ Installation completed successfully!"
echo "═══════════════════════════════════════════════════════════"
echo ""
echo "🚀 To start the application:"
echo ""
echo "   Terminal 1:"
echo "   php artisan serve"
echo ""
echo "   Terminal 2:"
echo "   npm run dev"
echo ""
echo "📍 Access the application at: http://localhost:8000"
echo ""
echo "🔑 Demo Accounts:"
echo "   Admin: admin@gmail.com / password"
echo "   Staff: petugas@gmail.com / password"
echo "   User:  budi@gmail.com / password"
echo ""
echo "═══════════════════════════════════════════════════════════"
