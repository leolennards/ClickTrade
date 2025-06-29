<?php 
    // Show all success messages using SweetAlert popups
    if (isset($success_msg)) {
        foreach ($success_msg as $success_msg) {
            echo '<script>swal("'.$success_msg.'", "", "success");</script>';
        }
    }

    // Show all warning messages using SweetAlert popups
    if (isset($warning_msg)) {
        foreach ($warning_msg as $warning_msg) {
            echo '<script>swal("'.$warning_msg.'", "", "warning");</script>';
        }
    }

    // Show all info messages using SweetAlert popups
    if (isset($info_msg)) {
        foreach ($info_msg as $info_msg) {
            echo '<script>swal("'.$info_msg.'", "", "info");</script>';
        }
    }

    // Show all error messages using SweetAlert popups
    if (isset($error_msg)) {
        foreach ($error_msg as $error_msg) {
            echo '<script>swal("'.$error_msg.'", "", "error");</script>';
        }
    }
?>