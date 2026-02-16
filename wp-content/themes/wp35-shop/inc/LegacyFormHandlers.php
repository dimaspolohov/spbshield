<?php
declare(strict_types=1);

/**
 * Legacy Form Handlers
 * 
 * Handles legacy form submissions using modeJs parameter
 * This class maintains backward compatibility with old forms
 * 
 * @package SpbShield
 * @since 1.0.0
 * @deprecated Consider migrating to AjaxHandlers class
 */

namespace SpbShield\Inc;

class LegacyFormHandlers {
    
    /**
     * reCAPTCHA secret key
     */
    private const RECAPTCHA_SECRET = '6LdZP1oUAAAAAERu4h8oA4VUMaU6WopwxW5uHb13';
    
    /**
     * Constructor - Register handlers
     */
    public function __construct() {
        add_action('init', [$this, 'handle_legacy_forms'], 1);
    }
    
    /**
     * Handle legacy forms based on modeJs parameter
     */
    public function handle_legacy_forms(): void {
        if (!isset($_REQUEST['modeJs'])) {
            return;
        }
        
        $mode = sanitize_text_field($_REQUEST['modeJs']);
        
        match($mode) {
            'contactForm' => $this->handle_contact_form(),
            'contactFormMain' => $this->handle_contact_form_main(),
            'formMain' => $this->handle_form_main(),
            'contactFormMainBanner3' => $this->handle_contact_form_banner(),
            'subscribeForm' => $this->handle_subscribe_form(),
            default => null
        };
    }
    
    /**
     * Validate reCAPTCHA
     * 
     * @param string $param Parameter name
     * @return bool Validation result
     */
    private function validate_recaptcha(string $param = 'token'): bool {
        // Temporarily disabled - always returns true
        return true;
        
        /* Original implementation:
        if (!isset($_POST[$param])) {
            return false;
        }
        
        $response = sanitize_text_field($_POST[$param]);
        $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '';
        
        $url = sprintf(
            'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s&remoteip=%s',
            self::RECAPTCHA_SECRET,
            $response,
            $remote_ip
        );
        
        $validation = file_get_contents($url);
        $result = json_decode($validation, true);
        
        return isset($result['success']) && $result['success'] === true;
        */
    }
    
    /**
     * Send form email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     */
    private function send_email(string $to, string $subject, string $message): void {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=' . get_bloginfo('charset')
        ];
        
        wp_mail($to, $subject, $message, $headers);
    }
    
    /**
     * Send JSON response and exit
     * 
     * @param string $message Response message
     * @param bool $status Response status
     */
    private function send_json_response(string $message, bool $status): never {
        echo json_encode([
            'message' => $message,
            'status' => $status
        ]);
        die();
    }
    
    /**
     * Check honeypot field
     * 
     * @return bool True if honeypot is empty (valid submission)
     */
    private function check_honeypot(): bool {
        return empty($_REQUEST['phone']);
    }
    
    /**
     * Handle contact form
     */
    private function handle_contact_form(): never {
        if (!$this->check_honeypot()) {
            exit;
        }
        
        if (!$this->validate_recaptcha()) {
            $this->send_json_response('Ошибка recaptcha', false);
        }
        
        $name = $_REQUEST['contact_name_author'] ?? '';
        $phone = $_REQUEST['contact_phone_author'] ?? '';
        $email = $_REQUEST['contact_email_author'] ?? '';
        
        if (empty($name) || empty($phone) || empty($email)) {
            $this->send_json_response('Не все поля заполнены', false);
        }
        
        $message = sprintf(
            '<p>Имя: %s</p><p>Телефон: %s</p><p>Email: %s</p>',
            esc_html($name),
            esc_html($phone),
            esc_html($email)
        );
        
        $to = get_option('admin_email');
        $subject = 'Сообщение с контактной формы';
        
        $this->send_email($to, $subject, $message);
        $this->send_json_response('Ваше сообщение успешно отправлено', true);
    }
    
    /**
     * Handle contact form main
     */
    private function handle_contact_form_main(): never {
        if (!$this->check_honeypot()) {
            exit;
        }
        
        if (!$this->validate_recaptcha()) {
            $this->send_json_response('Ошибка recaptcha', false);
        }
        
        $name = $_REQUEST['contacts_name'] ?? '';
        $phone = $_REQUEST['contacts_phone'] ?? '';
        $email = $_REQUEST['contacts_email'] ?? '';
        $user_message = $_REQUEST['contacts_message'] ?? '';
        
        if (empty($name) || empty($phone) || empty($email)) {
            $this->send_json_response('Не все поля заполнены', false);
        }
        
        $message = sprintf(
            '<p>Имя: %s</p><p>Телефон: %s</p><p>Email: %s</p><p>Сообщение: %s</p>',
            esc_html($name),
            esc_html($phone),
            esc_html($email),
            esc_html($user_message)
        );
        
        $to = get_option('admin_email');
        $subject = 'Сообщение с контактной формы';
        
        $this->send_email($to, $subject, $message);
        $this->send_json_response('Ваше сообщение успешно отправлено', true);
    }
    
    /**
     * Handle form main
     */
    private function handle_form_main(): never {
        if (!$this->check_honeypot()) {
            exit;
        }
        
        if (!$this->validate_recaptcha()) {
            $this->send_json_response('Ошибка recaptcha', false);
        }
        
        $name = $_REQUEST['form_name'] ?? '';
        $phone = $_REQUEST['form_phone'] ?? '';
        $email = $_REQUEST['form_email'] ?? '';
        $user_message = $_REQUEST['form_message'] ?? '';
        
        if (empty($name) || empty($phone) || empty($email)) {
            $this->send_json_response('Не все поля заполнены', false);
        }
        
        $message = sprintf(
            '<p>Имя: %s</p><p>Телефон: %s</p><p>Email: %s</p><p>Сообщение: %s</p>',
            esc_html($name),
            esc_html($phone),
            esc_html($email),
            esc_html($user_message)
        );
        
        $to = get_option('admin_email');
        $subject = 'Сообщение с контактной формы';
        
        $this->send_email($to, $subject, $message);
        $this->send_json_response('Ваше сообщение успешно отправлено', true);
    }
    
    /**
     * Handle contact form banner
     */
    private function handle_contact_form_banner(): never {
        if (!$this->check_honeypot()) {
            exit;
        }
        
        $value_f = $_REQUEST['valueF'] ?? '';
        if ($value_f !== 'dfsd3f') {
            exit;
        }
        
        if (!$this->validate_recaptcha()) {
            $this->send_json_response('Ошибка recaptcha', false);
        }
        
        $name = $_REQUEST['name_author3'] ?? '';
        $phone = $_REQUEST['phone_author3'] ?? '';
        $user_message = $_REQUEST['message_author3'] ?? '';
        
        if (empty($name) || empty($phone)) {
            $this->send_json_response('Не все поля заполнены', false);
        }
        
        $message = sprintf(
            '<p>Имя: %s</p><p>Телефон: %s</p><p>Сообщение: %s</p>',
            esc_html($name),
            esc_html($phone),
            esc_html($user_message)
        );
        
        $to = get_option('admin_email');
        $subject = 'Сообщение с контактной формы';
        
        $this->send_email($to, $subject, $message);
        $this->send_json_response('Ваше сообщение успешно отправлено', true);
    }
    
    /**
     * Handle subscribe form
     */
    private function handle_subscribe_form(): never {
        if (!$this->check_honeypot()) {
            exit;
        }
        
        if (!$this->validate_recaptcha()) {
            $this->send_json_response('Ошибка recaptcha', false);
        }
        
        $email = $_REQUEST['email_subscribe_author'] ?? '';
        
        if (empty($email)) {
            $this->send_json_response('Не все поля заполнены', false);
        }
        
        $message = sprintf('<p>Email: %s</p>', esc_html($email));
        
        $to = get_option('admin_email');
        $subject = 'Сообщение с формы подписки';
        
        $this->send_email($to, $subject, $message);
        $this->send_json_response('Ваше сообщение успешно отправлено', true);
    }
}
