<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Home';
require __DIR__ . '/includes/client_header.php';
?>
<section class="hero"><div class="wrap"><div class="hero-inner">
  <span class="eyebrow">Smart Assess Public Client Portal</span>
  <h1>Official Municipal Portal for Property &amp; Land Services</h1>
  <p class="lede">Digitalizing governance for the citizens of Mabini. Access property assessments, tax declarations, and municipal resources with clarity and integrity.</p>
  <a class="btn btn-hero" href="#services">Get Started <?= icon_span('arrowRight') ?></a>
</div></div></section>

<section class="section" id="services"><div class="wrap">
  <div class="section-head"><h2>How Can We Help You?</h2>
    <p>Choose the service that best matches your needs. We offer two main pathways to access municipal property assessment services:</p></div>
  <div class="service-grid">
    <div class="service-card"><div class="chip-icon"><?= icon('doc') ?></div>
      <h3>Start Document Request</h3>
      <p>Request for Certified True Copies of Tax Declarations, Certifications of Land Holdings, and more.</p>
      <a class="btn-link" href="/client/document-request.php">Begin Application <?= icon_span('arrowRight','15px') ?></a></div>
    <div class="service-card"><div class="chip-icon"><?= icon('swap') ?></div>
      <h3>Land Transfer</h3>
      <p>Submit requirements for property transfer, consolidation, or subdivision of land records.</p>
      <a class="btn-link" href="/client/land-transfer.php">Land Transfer <?= icon_span('arrowRight','15px') ?></a></div>
  </div>
</div></section>

<section class="section section-alt"><div class="wrap">
  <div class="section-head"><h2>Why Choose SMART ASSESS?</h2>
    <p>Experience the future of municipal services with our digital platform designed for efficiency, transparency, and accessibility.</p></div>
  <div class="feature-row">
    <div><div class="chip-icon"><?= icon('shield') ?></div><h4>Secure &amp; Reliable</h4><p>Your data is protected with enterprise-grade security. All transactions are encrypted and monitored.</p></div>
    <div><div class="chip-icon"><?= icon('clock') ?></div><h4>Fast Processing</h4><p>Streamlined workflows ensure your requests are processed quickly with real-time status updates.</p></div>
    <div><div class="chip-icon"><?= icon('headset') ?></div><h4>24/7 Support</h4><p>Access our services anytime. Our support team is here to help with any questions or concerns.</p></div>
  </div>
</div></section>

<?php require __DIR__ . '/includes/site_footer.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
