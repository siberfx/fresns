<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Fresns Installation Language Lines
    |--------------------------------------------------------------------------
    */

    // commons
    'title' => 'پیکربندی فایل تنظیمات',
    'desc' => 'نصب',
    'btn_check' => 'دوباره امتحان کنید',
    'btn_next' => 'مرحله بعد',
    'btn_submit' => 'ارسال',
    // intro
    'intro_title' => 'به Fresns خوش آمدید',
    'intro_desc' => 'قبل از شروع، نیاز به اطلاعاتی در مورد پایگاه داده داریم. موارد زیر را باید بدانید قبل از ادامه:',
    'intro_database_name' => 'نام پایگاه داده',
    'intro_database_username' => 'نام کاربری پایگاه داده',
    'intro_database_password' => 'رمز عبور پایگاه داده',
    'intro_database_host' => 'میزبان پایگاه داده',
    'intro_database_table_prefix' => 'پیشوند جدول (اگر می‌خواهید بیش از یک Fresns را در یک پایگاه داده اجرا کنید)',
    'intro_database_desc' => 'احتمالاً این اطلاعات توسط میزبان وب شما ارائه شده است. اگر این اطلاعات را ندارید، باید قبل از ادامه با آن‌ها تماس بگیرید. اگر آماده‌اید...',
    'intro_next_btn' => 'بزن بریم!',
    // server
    'server_title' => 'نیازمندی‌های سرور',
    'server_check_php_version' => 'PHP 8.2+',
    'server_check_composer_version' => 'Composer 2.5+',
    'server_check_https' => 'HTTPS برای سایت‌ها توصیه می‌شود',
    'server_check_folder_ownership' => 'مالکیت پوشه',
    'server_check_php_extensions' => 'افزونه‌های PHP',
    'server_check_php_functions' => 'توابع PHP',
    'server_check_error' => 'شکست در تشخیص محیط سرور.',
    'server_check_self' => 'بررسی خودکار',
    'server_status_success' => 'موافق',
    'server_status_failure' => 'خطا',
    'server_status_warning' => 'هشدار',
    'server_status_not_writable' => 'قابل نوشتن نیست',
    'server_status_not_installed' => 'نصب نشده',
    'server_status_not_enabled' => 'فعال نشده',
    // database
    'database_title' => 'اطلاعات پایگاه داده',
    'database_desc' => 'در زیر باید جزئیات اتصال پایگاه داده خود را وارد کنید. اگر مطمئن نیستید، با میزبان خود تماس بگیرید.',
    'database_driver' => 'پایگاه داده',
    'database_name' => 'نام پایگاه داده',
    'database_name_sqlite' => 'مسیر پایگاه داده',
    'database_name_desc' => 'نام پایگاه داده‌ای که می‌خواهید با Fresns استفاده کنید.',
    'database_username' => 'نام کاربری',
    'database_username_desc' => 'نام کاربری پایگاه داده شما.',
    'database_password' => 'رمز عبور',
    'database_password_desc' => 'رمز عبور پایگاه داده شما.',
    'database_host' => 'میزبان پایگاه داده',
    'database_host_desc' => 'اگر localhost کار نمی‌کند، باید این اطلاعات را از میزبان وب خود دریافت کنید.',
    'database_port' => 'پورت پایگاه داده',
    'database_port_mysql_desc' => 'پورت پیش‌فرض 3306',
    'database_port_pgsql_desc' => 'پورت پیش‌فرض 5432',
    'database_port_sqlsrv_desc' => 'پورت پیش‌فرض 1433',
    'database_timezone' => 'منطقه زمانی پایگاه داده',
    'database_timezone_desc' => 'پیکربندی صحیح تضمین می‌کند که زمان داده‌ها دقیق است تا Fresns بتواند زمان را به درستی پردازش کند.',
    'database_table_prefix' => 'پیشوند جدول',
    'database_table_prefix_desc' => 'اگر می‌خواهید چندین نصب Fresns را در یک پایگاه داده اجرا کنید، این را تغییر دهید.',
    'database_config_invalid' => 'پیکربندی پایگاه داده نامعتبر',
    'database_import_log' => 'گزارش وارد کردن داده‌ها',
    // install
    'install_failure' => 'نصب ناموفق بود، لطفاً برای علت به گزارش لاگ مراجعه کنید',
    // register
    'register_welcome' => 'به فرآیند نصب Fresns خوش آمدید! فقط اطلاعات زیر را پر کنید و در مسیر استفاده از قدرتمندترین و چندسکویی‌ترین نرم‌افزار شبکه اجتماعی دنیا قرار خواهید گرفت.',
    'register_title' => 'اطلاعات مورد نیاز',
    'register_desc' => 'لطفاً اطلاعات زیر را ارائه دهید. نگران نباشید، می‌توانید این تنظیمات را بعداً تغییر دهید.',
    'register_account_email' => 'ایمیل مدیر',
    'register_account_password' => 'رمز عبور',
    'register_account_password_confirm' => 'تأیید رمز عبور',
    // done
    'done_title' => 'موفقیت!',
    'done_desc' => 'Fresns نصب شد. متشکریم و لذت ببرید!',
    'done_account' => 'حساب کاربری',
    'done_password' => 'رمز عبور',
    'done_password_desc' => 'رمز عبور انتخابی شما.',
    'done_btn' => 'ورود',
];
