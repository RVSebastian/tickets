<?php
  session_destroy();
  session_reset();
  header("Location: login");

?>