<?php // terms of service page ?>
<?php $pageTitle='Terms & Conditions'; require_once __DIR__.'/includes/bootstrap.php'; require_once APP_ROOT.'/includes/header.php'; ?>
<div class="card"><div class="card-body p-4">
<h2 class="mb-4"><i class="fas fa-file-contract me-2 text-danger"></i>Terms & Conditions</h2>
<p class="text-muted">Last updated: <?=date('F Y')?></p>
<h5>1. Acceptance of Terms</h5>
<p>By registering and using BloodLink, you agree to these terms. If you do not agree, please do not use the platform.</p>
<h5>2. User Responsibilities</h5>
<p>You must provide accurate personal information, including your blood type and location. Providing false medical information may endanger lives and will result in account termination.</p>
<h5>3. Donor Obligations</h5>
<p>Donors must meet all health eligibility requirements. A minimum interval of 12 weeks (90 days) is enforced between donations. Donors must not donate blood if they are feeling unwell, are on medication, or have any condition that would make donation unsafe.</p>
<h5>4. Hospital Obligations</h5>
<p>Hospitals must hold a valid license and provide accurate contact details. Hospital accounts require admin verification before creating blood requests. Hospitals must follow proper medical protocols during all donation processes.</p>
<h5>5. Privacy & Data Protection</h5>
<p>Your personal data is used solely for blood donation coordination. Location data is stored as area-level only (no exact coordinates from users). We do not share your data with third parties. All passwords and security answers are stored using industry-standard encryption.</p>
<h5>6. Booking & Donation</h5>
<p>Bookings are not guaranteed until confirmed by the hospital. Donors should arrive at the scheduled time. Hospitals may cancel or reject bookings based on operational needs. Both parties should communicate changes promptly.</p>
<h5>7. Medical Disclaimer</h5>
<p>BloodLink is a coordination platform only. We do not provide medical advice. All donation procedures are performed by qualified medical professionals at the hospital. Donors should consult their physician if they have any health concerns.</p>
<h5>8. Account Termination</h5>
<p>If you violate these terms, provide false medical information, misuse the booking system (such as repeated fraudulent bookings or cancellations), or engage in abusive behaviour toward other users or staff, your account will be permanently deleted by an administrator. Once deleted, you will not be able to log in or create a new account using the same email address. A reason for the deletion will be recorded for our records.</p>
<h5>9. Limitation of Liability</h5>
<p>BloodLink is not responsible for any medical complications arising from blood donation. By using this platform, you acknowledge that donation carries inherent risks and you participate voluntarily.</p>
<h5>10. Changes to Terms</h5>
<p>We may update these terms at any time. Continued use of the platform constitutes acceptance of updated terms.</p>
<p class="mt-4"><strong>By creating an account, you confirm that you have read, understood, and agree to these Terms & Conditions.</strong></p>
</div></div>
<?php require_once APP_ROOT.'/includes/footer.php';?>
