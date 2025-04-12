<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Audio_Analyzer {

    private $essentia;
    private $supported_formats = ['mp3', 'wav', 'flac', 'ogg'];

    public function __construct() {
        add_action('wp_ajax_brmedia_analyze_audio', [$this, 'ajax_analyze']);
        add_action('add_attachment', [$this, 'process_uploaded_audio']);
    }

    public function process_uploaded_audio($attachment_id) {
        $file_path = get_attached_file($attachment_id);
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);

        if (!in_array(strtolower($extension), $this->supported_formats)) {
            return;
        }

        $analysis = $this->analyze($file_path);

        update_post_meta($attachment_id, '_brmedia_bpm', $analysis['bpm']);
        update_post_meta($attachment_id, '_brmedia_key', $analysis['key']);
        update_post_meta($attachment_id, '_brmedia_duration', $analysis['duration']);
    }

    public function analyze($file_path) {
        try {
            $audio_data = $this->load_audio_file($file_path);
            
            return [
                'bpm' => $this->detect_bpm($audio_data),
                'key' => $this->detect_key($audio_data),
                'duration' => $this->get_duration($file_path)
            ];
        } catch (Exception $e) {
            error_log('BRMedia Audio Analysis Error: ' . $e->getMessage());
            return false;
        }
    }

    private function load_audio_file($path) {
        if (!file_exists($path)) {
            throw new Exception("File not found: {$path}");
        }

        // Convert to PCM WAV for analysis
        $temp_file = wp_tempnam();
        exec("ffmpeg -i {$path} -acodec pcm_s16le -ar 44100 {$temp_file}");

        $audio_data = file_get_contents($temp_file);
        unlink($temp_file);

        return $audio_data;
    }

    private function detect_bpm($audio_data) {
        // Essentia.js integration
        $essentia = new Essentia(EssentiaWASM);
        $result = $essentia->RhythmExtractor($audio_data);
        return round($result['bpm']);
    }

    private function detect_key($audio_data) {
        $essentia = new Essentia(EssentiaWASM);
        $result = $essentia->KeyExtractor($audio_data);
        return $result['key'];
    }

    private function get_duration($file_path) {
        $getID3 = new getID3;
        $file_info = $getID3->analyze($file_path);
        return $file_info['playtime_seconds'] ?? 0;
    }

    public function ajax_analyze() {
        check_ajax_referer('brmedia_audio_nonce', 'security');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('Permission denied', 'brmedia'), 403);
        }

        $attachment_id = absint($_POST['attachment_id']);
        $file_path = get_attached_file($attachment_id);

        if (!$file_path || !file_exists($file_path)) {
            wp_send_json_error(__('File not found', 'brmedia'), 404);
        }

        $analysis = $this->analyze($file_path);

        if ($analysis) {
            wp_send_json_success($analysis);
        }

        wp_send_json_error(__('Analysis failed', 'brmedia'), 500);
    }
}