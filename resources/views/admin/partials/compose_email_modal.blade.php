<!-- Compose Email Modal -->
<div id="composeEmailModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
  <div class="modal-content" style="background:#fff; border-radius:8px; width:90%; max-width:600px; padding:24px; position:relative;">
    <span class="close" style="position:absolute; top:16px; right:24px; font-size:24px; cursor:pointer;">&times;</span>
    <h2 style="margin-bottom:16px;">Compose Email</h2>
    <form id="composeEmailForm" enctype="multipart/form-data">
      <div class="form-group">
        <label for="recipients">To:</label>
        <input type="text" id="recipients" name="recipients" class="form-control" readonly style="background:#f5f5f5;" />
      </div>
      <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" class="form-control" value="Meeting Registration Confirmation" required />
      </div>
      <div class="form-group">
        <label for="body">Message:</label>
        <textarea id="body" name="body" class="form-control" rows="8" required>
            <p>Hello <b>[Name]</b>,</p>
            <p>Your registration was successful!</p>
            <p>
                <b>Your Meeting Code:</b> 
                <span style="color:#42CCC5;">[Meeting Code]</span><br>
                Please keep this code safe. You will need it to verify your attendance at the meeting.
            </p>
            <p>Best regards,<br>
                <b>CocoaSpace Team</b>
            </p>
        </textarea>
      </div>
      <div class="form-group">
        <label for="attachments">Attachments:</label>
        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple />
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top:12px;">Send Email</button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
const defaultEmailTemplate = `<p>Hello <b>[Name]</b>,</p><p>Your registration was successful!</p><p><b>Your Meeting Code:</b> <span style='color:#42CCC5;'>[Meeting Code]</span><br>Please keep this code safe. You will need it to verify your attendance at the meeting.</p><p>Best regards,<br><b>CocoaSpace Team</b></p>`;

CKEDITOR.replace('body');
CKEDITOR.instances.body.on('instanceReady', function() {
  CKEDITOR.instances.body.setData(defaultEmailTemplate);
});

function showComposeEmailModal(recipients) {
  document.getElementById('recipients').value = recipients;
  document.getElementById('subject').value = 'Meeting Registration Confirmation';
  document.getElementById('body').value = defaultEmailTemplate;
  document.getElementById('composeEmailModal').style.display = 'flex';
}

if(document.getElementById('composeEmailModal')) {
  document.querySelector('#composeEmailModal .close').onclick = function() {
    document.getElementById('composeEmailModal').style.display = 'none';
  };
}

const composeEmailForm = document.getElementById('composeEmailForm');
if (composeEmailForm) {
  composeEmailForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(composeEmailForm);
    const sendBtn = composeEmailForm.querySelector('button[type="submit"]');
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    fetch('/admin/attendees/send-custom-email', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      sendBtn.disabled = false;
      sendBtn.textContent = 'Send Email';
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Email(s) sent successfully!'
        });
        document.getElementById('composeEmailModal').style.display = 'none';
        composeEmailForm.reset();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message || 'Failed to send email.'
        });
      }
    })
    .catch(() => {
      sendBtn.disabled = false;
      sendBtn.textContent = 'Send Email';
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to send email.'
      });
    });
  });
}
</script>

<style>
.swal2-container {
  z-index: 20000 !important;
}
</style> 