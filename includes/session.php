<?php
// Session utility - ensures session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
