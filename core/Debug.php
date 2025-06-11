<?php
class Debug {
    private static $messages = [];
    private static $enabled = true;

    public static function log($message) {
        if (self::$enabled) {
            self::$messages[] = date('H:i:s') . ' - ' . $message;
        }
    }

    public static function getMessages() {
        return self::$messages;
    }

    public static function render() {
        if (!self::$enabled || empty(self::$messages)) {
            return;
        }

        echo '<div id="debug-panel" style="
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 400px;
            max-height: 300px;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            z-index: 9999;
        ">';
        echo '<div style="margin-bottom: 10px; border-bottom: 1px solid #666; padding-bottom: 5px;">
                <strong>Debug Messages</strong>
                <button onclick="document.getElementById(\'debug-panel\').style.display=\'none\'" 
                        style="float: right; background: none; border: none; color: #fff; cursor: pointer;">
                    ×
                </button>
            </div>';
        foreach (self::$messages as $message) {
            echo '<div style="margin-bottom: 5px; border-bottom: 1px solid #333; padding-bottom: 5px;">' 
                . htmlspecialchars($message) 
                . '</div>';
        }
        echo '</div>';
    }
}
