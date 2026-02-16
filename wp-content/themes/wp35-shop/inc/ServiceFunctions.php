<?php
declare(strict_types=1);

/**
 * Service Functions
 * 
 * Utility functions for text processing, media handling, and WordPress customizations
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class ServiceFunctions {
    
    /**
     * Transliteration map
     */
    private const TRANSLIT_MAP = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '_', 'ы' => 'y', 'ъ' => '_',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '_'
    ];
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        // Disable emojis
        add_action('init', [$this, 'disable_emojis']);
        
        // SVG and WebP support
        add_filter('upload_mimes', [$this, 'allow_svg_webp_upload']);
        add_filter('wp_check_filetype_and_ext', [$this, 'fix_svg_mime_type'], 10, 5);
        add_filter('wp_prepare_attachment_for_js', [$this, 'show_svg_in_media_library']);
        
        // Clean output
        add_action('template_redirect', [$this, 'clean_html_output']);
        
        // Modify read more link
        add_filter('the_content_more_link', [$this, 'modify_read_more_link']);
    }
    
    /**
     * Generate excerpt with shortcode removal
     * 
     * @param string|array<string, mixed> $args Arguments
     * @param int $charlength Maximum character length
     * @param string $more_text Read more text
     * @return string Excerpt HTML
     */
    public static function excerpt(string|array $args = '', int $charlength = 320, string $more_text = 'Читать дальше...'): string {
        global $post;
        
        if (is_string($args)) {
            parse_str($args, $args);
        }
        
        $config = (object) array_merge([
            'maxchar' => $charlength,
            'text' => '',
            'autop' => true,
            'save_tags' => '',
            'more_text' => $more_text,
        ], is_array($args) ? $args : []);
        
        $config = apply_filters('kama_excerpt_args', $config);
        
        if (empty($config->text) && isset($post)) {
            $config->text = $post->post_excerpt ?: $post->post_content;
        }
        
        $text = $config->text;
        
        // Remove shortcodes
        $text = preg_replace('~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text);
        $text = preg_replace('~\[/?[^\]]*\](?!\()~', '', $text);
        $text = trim($text);
        
        $text_append = '';
        
        // Handle <!--more--> tag
        if (strpos($text, '<!--more-->') !== false) {
            preg_match('/(.*)<!--more-->/s', $text, $matches);
            $text = trim($matches[1] ?? '');
            
            if (isset($post)) {
                $text_append = sprintf(
                    ' <a href="%s#more-%d">%s</a>',
                    get_permalink($post),
                    $post->ID,
                    $config->more_text
                );
            }
        } else {
            $text = trim(strip_tags($text, $config->save_tags));
            
            // Truncate text
            if (mb_strlen($text) > $config->maxchar) {
                $text = mb_substr($text, 0, $config->maxchar);
                $text = preg_replace('~(.*)\s[^\s]*$~s', '\\1...', $text);
            }
        }
        
        // Convert line breaks to HTML
        if ($config->autop) {
            $text = preg_replace(
                ["/\r/", "/\n{2,}/", "/\n/", '~</p><br ?/?>~'],
                ['', '</p><p>', '<br />', '</p>'],
                $text
            );
        }
        
        $text = apply_filters('kama_excerpt', $text, $config);
        $text .= $text_append;
        
        return ($config->autop && $text) ? "<p>$text</p>" : $text;
    }
    
    /**
     * Truncate text by character length
     * 
     * @param string $text Text to truncate
     * @param int $charlength Maximum length
     * @return string Truncated text
     */
    public static function truncate_text(string $text, int $charlength): string {
        $charlength++;
        
        if (mb_strlen($text) <= $charlength) {
            return $text;
        }
        
        $subex = mb_substr($text, 0, $charlength - 5);
        $words = explode(' ', $subex);
        $last_word_length = mb_strlen($words[count($words) - 1]);
        
        if ($last_word_length > 0) {
            return mb_substr($subex, 0, -$last_word_length);
        }
        
        return $subex;
    }
    
    /**
     * Transliterate Russian text to Latin
     * 
     * @param string $string String to transliterate
     * @return string Transliterated string
     */
    public static function translit(string $string): string {
        $string = mb_strtolower($string);
        return strtr($string, self::TRANSLIT_MAP);
    }
    
    /**
     * Uppercase first character (multibyte safe)
     * 
     * @param string $str String to process
     * @return string Processed string
     */
    public static function mb_ucfirst(string $str): string {
        $first = mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8');
        $rest = mb_strtolower(mb_substr($str, 1, mb_strlen($str), 'UTF-8'), 'UTF-8');
        
        return $first . $rest;
    }
    
    /**
     * Disable WordPress emojis
     */
    public function disable_emojis(): void {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        add_filter('tiny_mce_plugins', [$this, 'disable_emojis_tinymce']);
        add_filter('wp_resource_hints', [$this, 'disable_emojis_remove_dns_prefetch'], 10, 2);
    }
    
    /**
     * Remove emoji plugin from TinyMCE
     * 
     * @param mixed $plugins TinyMCE plugins
     * @return array<int, string> Modified plugins
     */
    public function disable_emojis_tinymce(mixed $plugins): array {
        if (!is_array($plugins)) {
            return [];
        }
        
        return array_diff($plugins, ['wpemoji']);
    }
    
    /**
     * Remove emoji CDN from DNS prefetch
     * 
     * @param mixed $urls URLs for resource hints
     * @param mixed $relation_type Relation type
     * @return array<int, string> Modified URLs
     */
    public function disable_emojis_remove_dns_prefetch(mixed $urls, mixed $relation_type): array {
        if ($relation_type !== 'dns-prefetch' || !is_array($urls)) {
            return is_array($urls) ? $urls : [];
        }
        
        $emoji_url = 'https://s.w.org/images/core/emoji/';
        
        foreach ($urls as $key => $url) {
            if (is_string($url) && strpos($url, $emoji_url) !== false) {
                unset($urls[$key]);
            }
        }
        
        return $urls;
    }
    
    /**
     * Allow SVG and WebP uploads
     * 
     * @param mixed $mimes Current MIME types
     * @return array<string, string> Modified MIME types
     */
    public function allow_svg_webp_upload(mixed $mimes): array {
        if (!is_array($mimes)) {
            $mimes = [];
        }
        
        $mimes['svg'] = 'image/svg+xml';
        $mimes['ico'] = 'image/x-icon';
        $mimes['webp'] = 'image/webp';
        
        return $mimes;
    }
    
    /**
     * Fix SVG MIME type
     * 
     * @param mixed $data File data
     * @param mixed $file File path
     * @param mixed $filename File name
     * @param mixed $mimes MIME types
     * @param mixed $real_mime Real MIME type
     * @return array<string, mixed> Modified file data
     */
    public function fix_svg_mime_type(mixed $data, mixed $file, mixed $filename, mixed $mimes, mixed $real_mime = ''): array {
        if (!is_array($data)) {
            $data = [];
        }
        
        $ext = $data['ext'] ?? '';
        
        if (empty($ext) && is_string($filename)) {
            $exploded = explode('.', $filename);
            $ext = strtolower(end($exploded));
        }
        
        if ($ext === 'svg' || $ext === 'svgz') {
            $data['type'] = 'image/svg+xml';
            $data['ext'] = $ext;
        }
        
        return $data;
    }
    
    /**
     * Show SVG and WebP in media library
     * 
     * @param mixed $response Response data
     * @return array<string, mixed> Modified response
     */
    public function show_svg_in_media_library(mixed $response): array {
        if (!is_array($response)) {
            return [];
        }
        
        $mime = $response['mime'] ?? '';
        
        if ($mime === 'image/svg+xml' || $mime === 'image/webp') {
            $response['image'] = [
                'src' => $response['url'] ?? '',
            ];
        }
        
        return $response;
    }
    
    /**
     * Clean HTML output from type attributes
     */
    public function clean_html_output(): void {
        ob_start(function(string $buffer): string {
            $buffer = str_replace(['type="text/javascript"', "type='text/javascript'"], '', $buffer);
            $buffer = str_replace(['type="text/css"', "type='text/css'"], '', $buffer);
            $buffer = str_replace('<h2 class="awooc-form-custom-order-title"></h2>	', '<h2 class="awooc-form-custom-order-title">text</h2>', $buffer);
            
            return $buffer;
        });
    }
    
    /**
     * Modify read more link
     * 
     * @return string Read more link HTML
     */
    public function modify_read_more_link(): string {
        return '<a class="more-link" href="' . get_permalink() . '">(далее...)</a>';
    }
}
