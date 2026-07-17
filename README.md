# WooCommerce Order Item Meta Copier

Adds a copy button to WooCommerce order items for copying product variation and item meta values.

## Description

WooCommerce Order Item Meta Copier adds a copy button above each item metadata table on WooCommerce order edit pages.

Store administrators can copy product attributes, variation values, and other displayed order item metadata with one click.

The copied output places each metadata title and value on separate lines, with a blank line between items.

## Features

- Adds a copy button to each order item metadata table
- Copies variation attributes and item metadata
- Preserves readable line breaks
- Shows a success state after copying
- Uses the modern Clipboard API
- Includes a fallback for older browsers
- Detects dynamically reloaded order items
- Prevents duplicate buttons
- Loads only on WooCommerce order edit pages
- Compatible with WooCommerce HPOS
- No custom database tables
- Single-file plugin

## Requirements

- PHP 7.4+
- WordPress 6.0+
- WooCommerce

## Installation

1. Download the repository as a ZIP file.
2. Open **Plugins → Add New Plugin → Upload Plugin** in WordPress.
3. Select the downloaded ZIP file.
4. Install and activate the plugin.

## Usage

After activation, open a WooCommerce order in the WordPress admin panel.

A **Copy Values** button is displayed above each product metadata table.

Selecting the button copies the metadata in the following format:

```text
Color
Black

Size
Large

License
One year
```

## Clipboard Support

The plugin first attempts to use the browser Clipboard API.

If the Clipboard API is unavailable or the page is not running in a secure context, a legacy copy method is used automatically.

## HPOS Compatibility

The plugin declares compatibility with WooCommerce High-Performance Order Storage.

It supports:

- Legacy WooCommerce order edit screens
- HPOS order edit screens

## Data Storage

The plugin does not store, modify, or transmit any order information.

All copied content is processed locally inside the browser.

## Development

Built with:

- WordPress Coding Standards
- Native WordPress APIs
- Native JavaScript
- Clipboard API
- MutationObserver
- WooCommerce admin markup
- HPOS-compatible screen detection
- Lightweight single-file architecture

## Security

The plugin:

- Loads only in the WordPress admin panel
- Loads only on WooCommerce order screens
- Does not make AJAX requests
- Does not send data to external services
- Does not modify order data

## License

GPL-3.0

## Author

**Amirreza Shayesteh Far**

- Website: https://amirrezaa.ir/
- GitHub: https://github.com/amirrezashf
- Repository: https://github.com/amirrezashf/WooCommerce-Order-Item-Meta-Copier

---

# کپی متادیتای آیتم سفارش ووکامرس

افزودن دکمه کپی به آیتم‌های سفارش ووکامرس برای کپی کردن ویژگی‌های محصول، variationها و سایر اطلاعات نمایش‌داده‌شده.

## توضیحات

افزونه WooCommerce Order Item Meta Copier بالای جدول اطلاعات هر محصول در صفحه ویرایش سفارش ووکامرس، یک دکمه کپی اضافه می‌کند.

مدیر فروشگاه می‌تواند ویژگی‌های محصول، مقادیر variation و سایر metadataهای نمایش‌داده‌شده را با یک کلیک کپی کند.

عنوان و مقدار هر مورد در خط‌های جداگانه قرار می‌گیرند و بین موارد یک خط خالی اضافه می‌شود.

## ویژگی‌ها

- افزودن دکمه کپی برای هر جدول metadata
- کپی ویژگی‌های variation و اطلاعات آیتم سفارش
- حفظ line breakهای خوانا
- نمایش وضعیت موفقیت پس از کپی
- استفاده از Clipboard API
- دارای روش جایگزین برای مرورگرهای قدیمی
- شناسایی آیتم‌هایی که به‌صورت پویا reload می‌شوند
- جلوگیری از ایجاد دکمه تکراری
- بارگذاری فقط در صفحه ویرایش سفارش
- سازگار با WooCommerce HPOS
- بدون جدول اختصاصی دیتابیس
- معماری تک‌فایلی

## نیازمندی‌ها

- PHP 7.4+
- WordPress 6.0+
- WooCommerce

## نصب

1. repository را به‌صورت ZIP دانلود کنید.
2. در وردپرس وارد **افزونه‌ها ← افزودن افزونه تازه ← بارگذاری افزونه** شوید.
3. فایل ZIP را انتخاب کنید.
4. افزونه را نصب و فعال کنید.

## نحوه استفاده

پس از فعال‌سازی، یک سفارش ووکامرس را در پنل مدیریت باز کنید.

بالای جدول اطلاعات هر محصول، دکمه **کپی مقادیر** نمایش داده می‌شود.

خروجی کپی‌شده به شکل زیر است:

```text
رنگ
مشکی

اندازه
بزرگ

مدت اشتراک
یک سال
```

## پشتیبانی از Clipboard

افزونه ابتدا از Clipboard API مرورگر استفاده می‌کند.

اگر Clipboard API در دسترس نباشد یا سایت در secure context اجرا نشود، روش قدیمی کپی به‌صورت خودکار استفاده می‌شود.

## سازگاری با HPOS

افزونه سازگاری خود را با WooCommerce High-Performance Order Storage اعلام می‌کند.

هر دو محیط زیر پشتیبانی می‌شوند:

- صفحه قدیمی ویرایش سفارش
- صفحه سفارش HPOS

## ذخیره‌سازی داده

افزونه هیچ‌کدام از اطلاعات سفارش را ذخیره، ویرایش یا ارسال نمی‌کند.

فرایند کپی کاملاً داخل مرورگر انجام می‌شود.

## امنیت

افزونه:

- فقط در پنل مدیریت بارگذاری می‌شود
- فقط در صفحه سفارش ووکامرس اجرا می‌شود
- درخواست AJAX ندارد
- اطلاعاتی به سرویس خارجی ارسال نمی‌کند
- داده‌های سفارش را تغییر نمی‌دهد

## مجوز

GPL-3.0

## نویسنده

**Amirreza Shayesteh Far**

- وب‌سایت: https://amirrezaa.ir/
- GitHub: https://github.com/amirrezashf
- Repository: https://github.com/amirrezashf/WooCommerce-Order-Item-Meta-Copier
