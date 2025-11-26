<?php
class GuideClientController {

    public function loginPost() {
        $username = $_POST['account_id'];
        $password = $_POST['password'];

        $model = new GuideModel();
        $hdv = $model->login($username, $password);

        if ($hdv) {
            $_SESSION['hdv'] = $hdv;
            header("Location: ?act=hdv_dashboard");
        } else {
            header("Location: ?act=hdv_login&error=1");
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['hdv'])) {
            header("Location: ?act=hdv_login");
            exit;
        }
        require_once 'views/client_hdv/dashboard.php';
    }
}
