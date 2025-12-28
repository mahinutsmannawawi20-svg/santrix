#!/bin/bash
# =====================================================
# DEPLOYMENT SCRIPT - Dashboard Riyadlul Huda
# VPS: 109.111.53.245 (Ubuntu 20.04)
# =====================================================

set -e  # Exit on any error

echo "🚀 Starting Laravel Deployment..."

# ===== 1. UPDATE SYSTEM =====
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# ===== 2. INSTALL NGINX =====
echo "🌐 Installing Nginx..."
apt install nginx -y
systemctl enable nginx
systemctl start nginx

# ===== 3. INSTALL MYSQL =====
echo "🗄️ Installing MySQL..."
apt install mysql-server -y
systemctl enable mysql
systemctl start mysql

# ===== 4. INSTALL PHP 8.2 =====
echo "🐘 Installing PHP 8.2..."
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl -y

# ===== 5. INSTALL COMPOSER =====
echo "🎼 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# ===== 6. INSTALL NODE.JS =====
echo "📗 Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install nodejs -y

# ===== 7. CLONE REPOSITORY =====
echo "📥 Cloning repository..."
cd /var/www
rm -rf dashboard-riyadlul-huda
git clone https://github.com/mahinutsmannawawi20-svg/dashboard-riyadlul-huda.git
cd dashboard-riyadlul-huda

# ===== 8. INSTALL DEPENDENCIES =====
echo "📚 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies & building assets..."
npm install
npm run build

# ===== 9. CONFIGURE LARAVEL =====
echo "⚙️ Configuring Laravel..."
cp .env.example .env
php artisan key:generate

# ===== 10. SET PERMISSIONS =====
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/dashboard-riyadlul-huda
chmod -R 755 /var/www/dashboard-riyadlul-huda
chmod -R 775 storage bootstrap/cache

# ===== 11. CREATE NGINX CONFIG =====
echo "📝 Creating Nginx config..."
cat > /etc/nginx/sites-available/dashboard-riyadlul-huda << 'EOF'
server {
    listen 80;
    server_name 109.111.53.245;
    root /var/www/dashboard-riyadlul-huda/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

ln -sf /etc/nginx/sites-available/dashboard-riyadlul-huda /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

echo ""
echo "✅ ============================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "✅ ============================================="
echo ""
echo "📌 Next steps:"
echo "   1. Setup MySQL database (run: mysql -u root)"
echo "   2. Edit /var/www/dashboard-riyadlul-huda/.env"
echo "   3. Run: php artisan migrate"
echo ""
echo "🌐 Access: http://109.111.53.245"
echo ""
