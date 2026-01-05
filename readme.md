WPCMRG – Dynamic Promo Blocks for WordPress

WPCMRG is a lightweight WordPress plugin that allows administrators to create, manage, and display dynamic promotional blocks using a custom post type, shortcode, caching, and a REST API.

🚀 Features

Custom Post Type: Promo Blocks

Custom Meta Fields:

CTA Text

CTA URL

Priority (sorting)

Expire Date

Shortcode to display promo blocks

Admin settings page

Transient-based caching with configurable TTL

Manual cache clearing via AJAX

REST API endpoint for promo blocks

Conditional frontend stylesheet loading

📦 Installation

Copy the plugin folder into:

wp-content/plugins/wpcmrg


Activate the plugin from WordPress Admin → Plugins

Go to Settings → Dynamic Content to configure options

⚙️ Configuration (Admin Settings)

Navigate to:

WordPress Admin → Settings → Dynamic Content

Available Options
Option	Description
Enable Promo Blocks	Enables/disables promo block functionality
Maximum Promo Blocks	Limits how many promo blocks are shown
Cache TTL (minutes)	How long promo blocks are cached
Clear Cache	Manually clear cached promo blocks
🧩 Promo Blocks (Custom Post Type)

Promo Blocks are managed like regular posts.

Fields

Title – Promo heading

Content – Promo description

Featured Image – Optional image

CTA Text – Button text

CTA URL – Button link

Priority – Lower number = higher priority

Expire Date – Promo stops showing after this date

Promo blocks are automatically filtered so that expired promos are excluded.

🔤 Shortcode Usage

Use the following shortcode anywhere in posts or pages:

[dynamic_promo]


The plugin will:

Fetch valid promo blocks

Order by priority

Respect cache and admin limits

Load frontend styles only when needed

🗃️ Caching

Promo blocks are cached using WordPress transients.

Cache Key Format

promo_blocks_{cache_ttl}_{max_promo_blocks}


Cache automatically expires based on TTL or can be manually cleared from the admin panel.

🌐 REST API
Endpoint
GET /wp-json/dcm/v1/promos

Optional Query Parameters
Parameter	Description
cache_ttl	Cache TTL in minutes
max_promo_blocks	Max number of promo blocks
Example
GET /wp-json/dcm/v1/promos?cache_ttl=60&max_promo_blocks=3

🎨 Frontend Styling

Frontend styles are automatically loaded only when the shortcode is present.

File location:

/frontend/style.css

🔐 Security

Nonce verification for meta boxes

Sanitization of all saved fields

Secure AJAX endpoint with nonce

REST API is public (read-only)

🧠 Developer Notes

Main class: WPCMRG

Fully static class design

Uses WordPress best practices:

register_post_type

add_meta_box

add_shortcode

set_transient

register_rest_route

📄 License

GPL v2 or later

✨ Author

Ranjit Galsar
Built for dynamic content management and extensibility.
