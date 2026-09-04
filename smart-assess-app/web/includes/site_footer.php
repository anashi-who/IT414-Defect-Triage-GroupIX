<footer class="site-footer"><div class="wrap"><div class="footer-grid">
  <div><h5><?= esc(get_setting('office_name', OFFICE_NAME)) ?></h5><p>Municipal Hall, Poblacion, Mabini, Batangas, Philippines</p></div>
  <div><h5>Contact</h5><p>Phone: <?= esc(get_setting('office_phone', OFFICE_PHONE)) ?><br>Email: <?= esc(get_setting('office_email', OFFICE_EMAIL)) ?></p></div>
  <div><h5>Office Hours</h5><p><?= esc(get_setting('office_hours', 'Monday to Friday, 8:00 AM - 5:00 PM')) ?></p></div>
</div>
<div class="footer-bottom">
  <span>&copy; <?= date('Y') ?> Mabini Assessor Office. All rights reserved.</span>
  <span>Smart Assess &middot; AI Rule-Based Requirement Checker</span>
</div>
</div></footer>
