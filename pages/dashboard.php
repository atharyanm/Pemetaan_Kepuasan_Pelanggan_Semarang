<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Welcome to Dashboard</h5>
                <p class="card-text">Overview of Puskesmas satisfaction data.</p>
            </div>
        </div>
    </div>
</div>