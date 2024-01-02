<?php
class Alert
{
    private function GenerateAlert($type, $message)
    {
        return "
        <div class='alert alert-$type alert-dismissible fade show' role='alert'>
            $message
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
    ";
    }
    public function Alert()
    {
        $alert = '';

        if (isset($_SESSION['success'])) { //insert data success
            $alert = $this->GenerateAlert('success', $_SESSION['success']);
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['error'])) { //insert data error
            $alert = $this->GenerateAlert('danger', $_SESSION['error']);
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['errorL'])) { //login error
            $alert = $this->GenerateAlert('danger', $_SESSION['errorL']);
            unset($_SESSION['errorL']);
        }
        if (isset($_SESSION['successS'])) { //signup success
            $alert = $this->GenerateAlert('success', $_SESSION['successS']);
            unset($_SESSION['successS']);
        } else if (isset($_SESSION['errorS'])) { //signup error
            $alert = $this->GenerateAlert('danger', $_SESSION['errorS']);
            unset($_SESSION['errorS']);
        }
        if (isset($_SESSION['edit'])) { //signup success
            $alert = $this->GenerateAlert('primary', $_SESSION['edit']);
            unset($_SESSION['edit']);
        }
        if (isset($_SESSION['successAL'])) { //elimination success
            $alert = $this->GenerateAlert('info', $_SESSION['successAL']);
            unset($_SESSION['successAL']);
        }
        if (isset($_SESSION['delete'])) { // delete success
            $alert = $this->GenerateAlert('warning', $_SESSION['delete']);
            unset($_SESSION['delete']);
        }
        if (isset($_SESSION['successDEP'])) { //depreciation success
            $alert = $this->GenerateAlert('info', $_SESSION['successDEP']);
            unset($_SESSION['successDEP']);
        }
        /*if (isset($_SESSION['successSale'])) { //sale success
            $alert = $this->GenerateAlert('info', $_SESSION['successSale']);
            unset($_SESSION['successSale']);
        }*/

        
        return $alert;
    }
}