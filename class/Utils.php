<?php 

    class Bootstrap {
        public function displayAlert($message, $type) {
            echo '<div class="alert alert-'. $type .'" role="alert">';
            echo ''. $message .'';
            echo '</div>';
        }
    }

    class SweetAlert2 {
        private $type;
        private $message;
        private $redirectUrl;
    
        // Constructor to initialize the type, message, and redirect URL
        public function __construct($message, $type = 'info', $redirectUrl = '') {
            $this->message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            $this->type = $this->validateType($type);
            $this->redirectUrl = htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8');
        }
    
        // Validate the alert type
        private function validateType($type) {
            $validTypes = ['success', 'error', 'warning', 'info'];
            return in_array($type, $validTypes) ? $type : 'info';
        }
    
        // Generate the JavaScript for SweetAlert2 with optional redirect
        public function renderAlert() {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>';
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "' . ucfirst($this->type) . '",
                        text: "' . $this->message . '",
                        icon: "' . $this->type . '",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed && "' . $this->redirectUrl . '" !== "") {
                            window.location.href = "' . $this->redirectUrl . '";
                        }
                    });
                });
            </script>';
        }
    }

    
    

?>