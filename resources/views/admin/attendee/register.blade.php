@extends('layouts.admin-sidebar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

@php
    $defaultEmailSubject = 'Meeting Registration Confirmation';
    $defaultEmailBody = '<p>Hello <b>[Name]</b>,</p><p>Your registration was successful!</p><p><b>Your Meeting Code:</b> <span style="color:#42CCC5;">[Meeting Code]</span><br>Please keep this code safe. You will need it to verify your attendance at the meeting.</p><p>Best regards,<br><b>CocoaSpace Team</b></p>';
    $savedAttachments = $bookings->email_attachment_paths ?? [];
@endphp

<div class="modern-container">
    <div class="modern-info-bar">
        <div>
            <h1>Register Attendees</h1>
            <div class="modern-meeting-details">
                <span><strong>Requester / Title:</strong> {{ $bookings->requester }}</span>
                <span><strong>Date:</strong> {{ $bookings->date }}</span>
                <span><strong>Time:</strong> {{ $bookings->time }}</span>
                <span><strong>Room:</strong> {{ $bookings->meetingRoom->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="page-tabs">
        <button type="button" class="page-tab active" data-tab="attendees">Attendees</button>
        <button type="button" class="page-tab" data-tab="mail-template">Mail Template</button>
    </div>

    {{-- Attendees Tab --}}
    <div class="tab-panel active" id="tab-attendees">
        <div class="modern-actions">
            <form action="{{ route('admin.attendees.register', $bookings->id) }}" method="GET" class="modern-search-form">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone" class="modern-search-input">
                <button type="submit" class="modern-search-btn">🔍 Search</button>
                @if(request('search'))
                <a href="{{ route('admin.attendees.register', $bookings->id) }}" class="modern-clear-btn">Clear</a>
                @endif
            </form>
            <button type="button" id="openRegisterModal" class="modern-register-btn">+ Register Attendee</button>
        </div>

        @if($attendees->count())
        <div class="modern-table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Department / Company</th>
                        <th>Phone</th>
                        <th>Registration Time</th>
                        <th>Status</th>
                        <th>Verification Code</th>
                        <th>Mail Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendees as $attendee)
                        <tr class="modern-card-row" data-attendee-id="{{ $attendee->id }}">
                            <td>{{ $attendee->name ?? 'N/A'}}</td>
                            <td>{{ $attendee->gender ?? 'N/A'}}</td>
                            <td>{{ $attendee->email ?? 'N/A'}}</td>
                            <td>{{ $attendee->department ?? 'N/A'}}</td>
                            <td>{{ $attendee->phone ?? 'N/A'}}</td>
                            <td>{{ $attendee->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($attendee->status === 'present')
                                    <span class="modern-badge badge-present">Present</span>
                                @else
                                    <span class="modern-badge badge-notpresent">Not Present</span>
                                @endif
                            </td>
                            <td>
                                @if($attendee->status === 'present')
                                    <span class="modern-code code-present">{{ $attendee->meeting_code }}</span>
                                @else
                                    <span class="modern-code code-notpresent">{{ $attendee->meeting_code }}</span>
                                @endif
                            </td>
                            <td class="mail-status-cell">
                                @if(($attendee->email_status ?? null) === 'sent')
                                    <span class="modern-badge badge-mail-sent">Sent</span>
                                @else
                                    <div class="mail-failed-wrap">
                                        <span class="modern-badge badge-mail-failed">{{ ($attendee->email_status ?? null) === 'failed' ? 'Failed' : 'Not Sent' }}</span>
                                        <button type="button" class="resend-mail-btn" data-attendee-id="{{ $attendee->id }}">Resend</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 16px;">
            {{ $attendees->withQueryString()->links() }}
        </div>
        @else
            <p class="modern-empty"><strong>No attendees have been registered for this meeting yet.</strong></p>
        @endif
    </div>

    {{-- Mail Template Tab --}}
    <div class="tab-panel" id="tab-mail-template">
        <p class="template-hint">
            Set the email that will be sent automatically when an attendee registers.
            Use <code>[Name]</code> and <code>[Meeting Code]</code> as placeholders.
        </p>

        <form id="mailTemplateForm" enctype="multipart/form-data" class="mail-template-form">
            @csrf
            <div class="form-group">
                <label for="email_subject" class="form-label">Subject *</label>
                <input
                    type="text"
                    name="email_subject"
                    id="email_subject"
                    class="form-input"
                    required
                    value="{{ $bookings->email_subject ?: $defaultEmailSubject }}"
                >
            </div>

            <div class="form-group">
                <label for="email_body" class="form-label">Message *</label>
                <textarea name="email_body" id="email_body" class="form-input email-body-editor" rows="14">{{ $bookings->email_body ?: $defaultEmailBody }}</textarea>
            </div>

            <div class="form-group">
                <label for="attachments" class="form-label">Attachments (optional)</label>
                <input type="file" name="attachments[]" id="attachments" class="form-input" multiple>
                <label class="clear-attachments-label">
                    <input type="checkbox" name="clear_attachments" id="clear_attachments" value="1">
                    Clear existing saved attachments
                </label>
            </div>

            @if(count($savedAttachments))
            <div class="saved-attachments">
                <strong>Current attachments:</strong>
                <ul>
                    @foreach($savedAttachments as $path)
                        <li>
                            <a href="{{ asset('storage/' . $path) }}" target="_blank">{{ basename($path) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <button type="submit" class="submit-button" id="saveTemplateBtn">Save Mail Template</button>
        </form>
    </div>
</div>

<!-- Registration Modal -->
<div id="registerModal" class="register-modal">
    <div class="register-modal-content">
        <span class="register-modal-close" id="closeRegisterModal">&times;</span>
        <div class="form-header">
            <h2 class="form-title">Registration Form</h2>
            <p class="form-subtitle">Join us for an unforgettable experience</p>
        </div>
        <form class="registration-form" action="/admin/attendees/register-ajax" method="POST" id="registerForm">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $bookings->id }}">
            <input type="hidden" name="registration_time" id="registration_time" value="">

            <div class="form-group">
                <label for="name" class="form-label">First Name *</label>
                <input type="text" name="name" id="name" required placeholder="Enter full name" class="form-input" style="text-transform: uppercase;" />
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" name="email" id="email" required placeholder="Enter email address" class="form-input" />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        required
                        inputmode="numeric"
                        minlength="10"
                        maxlength="10"
                        pattern="[0-9]{10}"
                        title="Enter a 10-digit phone number"
                        placeholder="0244444444"
                        class="form-input"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)"
                    />
                </div>
                <div class="form-group">
                    <label for="gender" class="form-label">Gender *</label>
                    <select name="gender" id="gender" class="form-input" required style="text-transform: uppercase;">
                        <option value="" disabled selected>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="department" class="form-label">Company/Organization/Department *</label>
                <input
                    type="text"
                    name="department"
                    id="department"
                    required
                    placeholder="Enter company, organization or department"
                    class="form-input"
                    style="text-transform: uppercase;"
                    value="{{ strtoupper(trim(collect([$bookings->company, $bookings->department])->filter()->implode(' / '))) }}"
                />
            </div>

            <button type="submit" class="submit-button">Register Now</button>
        </form>
    </div>
</div>

<style>
.modern-container {
    max-width: 1300px;
    margin: 40px auto 0 auto;
    padding: 32px 24px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(34, 197, 194, 0.08), 0 1.5px 4px rgba(0,0,0,0.04);
}
.modern-info-bar {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 20px;
}
.modern-info-bar h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #0f766e;
    margin: 0 0 8px 0;
    letter-spacing: 1px;
    text-align: left;
}
.modern-meeting-details {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    font-size: 1.08rem;
    color: #444;
    font-weight: 500;
}
.page-tabs {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 24px;
}
.page-tab {
    padding: 12px 22px;
    border: none;
    background: transparent;
    color: #64748b;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
}
.page-tab.active {
    color: #0f766e;
    border-bottom-color: #42CCC5;
}
.page-tab:hover {
    color: #0f766e;
}
.tab-panel {
    display: none;
}
.tab-panel.active {
    display: block;
}
.modern-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.modern-search-form {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}
.modern-search-input {
    padding: 10px 14px;
    border: 1.5px solid #42CCC5;
    border-radius: 7px;
    font-size: 1rem;
    outline: none;
    background: #fff;
}
.modern-search-input:focus {
    border: 1.5px solid #0f766e;
}
.modern-search-btn {
    padding: 10px 18px;
    background: linear-gradient(90deg, #42CCC5 60%, #0f766e 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    font-weight: 600;
    cursor: pointer;
}
.modern-clear-btn {
    padding: 10px 18px;
    background: #fff;
    color: #0f766e;
    border-radius: 7px;
    font-weight: 600;
    text-decoration: underline;
    border: 1.5px solid #42CCC5;
}
.modern-register-btn {
    padding: 10px 20px;
    background: #0f766e;
    color: #fff;
    border: none;
    border-radius: 7px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.modern-register-btn:hover {
    background: #42CCC5;
}
.modern-table-wrapper {
    margin-top: 18px;
    overflow-x: auto;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(34, 197, 194, 0.06);
    background: #f9f9fb;
}
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}
.modern-table thead th {
    background: #42CCC5;
    color: #fff;
    font-weight: 600;
    padding: 14px 10px;
    border-radius: 8px 8px 0 0;
    font-size: 1rem;
    text-align: left;
}
.modern-table tbody tr.modern-card-row {
    background: #fff;
    box-shadow: 0 2px 8px rgba(34, 197, 194, 0.07);
    border-radius: 8px;
}
.modern-table td {
    padding: 14px 10px;
    font-size: 1rem;
    color: #222;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}
.modern-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: capitalize;
}
.badge-present {
    background: linear-gradient(90deg, #42CCC5 60%, #0f766e 100%);
    color: #fff;
}
.badge-notpresent {
    background: #e5e7eb;
    color: #222;
}
.badge-mail-sent {
    background: #d1fae5;
    color: #059669;
}
.badge-mail-failed {
    background: #fee2e2;
    color: #dc2626;
}
.mail-failed-wrap {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}
.resend-mail-btn {
    padding: 6px 12px;
    background: #0f766e;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
}
.resend-mail-btn:hover {
    background: #42CCC5;
}
.resend-mail-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
.modern-code {
    font-weight: 700;
    font-size: 1.05rem;
    letter-spacing: 1px;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-block;
}
.code-present {
    background: #d1fae5;
    color: #059669;
}
.code-notpresent {
    background: #fee2e2;
    color: #dc2626;
}
.modern-empty {
    text-align: center;
    color: #ef4444;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 32px 0;
}
.template-hint {
    background: #f0fdfa;
    border: 1px solid #99f6e4;
    border-radius: 10px;
    padding: 14px 16px;
    color: #0f766e;
    margin-bottom: 20px;
    font-size: 0.95rem;
}
.template-hint code {
    background: #ccfbf1;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 700;
}
.mail-template-form {
    max-width: 900px;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.email-body-editor {
    min-height: 320px;
}
.cke_chrome {
    border: 1.5px solid #42CCC5 !important;
    border-radius: 10px !important;
    overflow: hidden;
}
.cke_top {
    background: #f0fdfa !important;
    border-bottom: 1px solid #99f6e4 !important;
}
.clear-attachments-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    font-size: 0.9rem;
    color: #64748b;
}
.saved-attachments {
    background: #f8fafc;
    border-radius: 8px;
    padding: 12px 14px;
}
.saved-attachments ul {
    margin: 8px 0 0;
    padding-left: 18px;
}
.saved-attachments a {
    color: #0f766e;
    font-weight: 600;
}

/* Modal */
.register-modal {
    display: none;
    position: fixed;
    z-index: 3000;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.swal2-container {
    z-index: 20000 !important;
}
.register-modal-content {
    background: #fff;
    border-radius: 16px;
    padding: 28px 28px 24px;
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 20px 40px rgba(66, 204, 197, 0.2);
    border: 1px solid rgba(66, 204, 197, 0.25);
}
.register-modal-close {
    position: absolute;
    top: 12px;
    right: 18px;
    font-size: 28px;
    color: #888;
    cursor: pointer;
    line-height: 1;
}
.register-modal-close:hover {
    color: #0f766e;
}
.form-header {
    text-align: center;
    margin-bottom: 1.25rem;
}
.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    background: linear-gradient(135deg, #42ccc5 0%, #0f766e 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.form-subtitle {
    color: rgb(71, 85, 105);
    font-size: 0.95rem;
}
.registration-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.form-row {
    display: flex;
    gap: 1rem;
}
.form-group {
    flex: 1;
}
.form-label {
    display: block;
    color: rgb(30, 41, 59);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid rgba(203, 213, 225, 0.8);
    border-radius: 10px;
    font-size: 0.9rem;
    outline: none;
    background: #fff;
    box-sizing: border-box;
}
.form-input:focus {
    border-color: #42ccc5;
    box-shadow: 0 0 0 3px rgba(66, 204, 197, 0.1);
}
.submit-button {
    width: 100%;
    background: linear-gradient(135deg, #42ccc5 0%, #14b8a6 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.25rem;
}
.submit-button:hover {
    box-shadow: 0 8px 25px rgba(66, 204, 197, 0.4);
}
.submit-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

@media (max-width: 700px) {
    .form-row {
        flex-direction: column;
    }
    .modern-actions {
        flex-direction: column;
        align-items: stretch;
    }
    .modern-register-btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let mailEditorReady = false;

    function initMailEditor() {
        if (mailEditorReady || !window.CKEDITOR || !document.getElementById('email_body')) {
            return;
        }
        if (CKEDITOR.instances.email_body) {
            mailEditorReady = true;
            return;
        }

        CKEDITOR.replace('email_body', {
            height: 360,
            removePlugins: 'exportpdf',
            allowedContent: true,
            toolbar: [
                { name: 'document', items: ['Source', '-', 'NewPage', 'Preview'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ]
        });

        mailEditorReady = true;
    }

    // Tabs
    document.querySelectorAll('.page-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');

            if (tab.dataset.tab === 'mail-template') {
                setTimeout(initMailEditor, 50);
            }
        });
    });

    // Resend email
    document.querySelectorAll('.resend-mail-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const attendeeId = btn.getAttribute('data-attendee-id');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            btn.disabled = true;
            btn.textContent = 'Sending...';

            Swal.fire({
                title: 'Resending...',
                text: 'Sending confirmation email.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/admin/attendees/${attendeeId}/resend-email`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sent',
                        text: data.message || 'Email resent successfully.',
                        confirmButtonColor: '#42CCC5'
                    }).then(() => window.location.reload());
                } else {
                    btn.disabled = false;
                    btn.textContent = 'Resend';
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.message || 'Failed to resend email.'
                    });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Resend';
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to resend email.'
                });
            });
        });
    });

    // Save mail template
    const mailTemplateForm = document.getElementById('mailTemplateForm');
    if (mailTemplateForm) {
        mailTemplateForm.addEventListener('submit', function (e) {
            e.preventDefault();
            initMailEditor();

            if (window.CKEDITOR && CKEDITOR.instances.email_body) {
                document.getElementById('email_body').value = CKEDITOR.instances.email_body.getData();
            }

            const bodyValue = (document.getElementById('email_body').value || '').replace(/<[^>]*>/g, '').trim();
            if (!bodyValue) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Message required',
                    text: 'Please enter the email message.'
                });
                return;
            }

            const btn = document.getElementById('saveTemplateBtn');
            btn.disabled = true;
            btn.textContent = 'Saving...';

            const formData = new FormData(mailTemplateForm);
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('admin.attendees.mail-template', $bookings->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Save Mail Template';
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: data.message || 'Mail template saved successfully.',
                        confirmButtonColor: '#42CCC5'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to save template.'
                    });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Save Mail Template';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save template.'
                });
            });
        });
    }

    // Register modal
    const modal = document.getElementById('registerModal');
    const openBtn = document.getElementById('openRegisterModal');
    const closeBtn = document.getElementById('closeRegisterModal');
    const registerForm = document.getElementById('registerForm');

    openBtn.onclick = function () {
        modal.style.display = 'flex';
    };
    closeBtn.onclick = function () {
        modal.style.display = 'none';
    };
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const sendRegistration = (token) => {
                if (token) {
                    const tokenInput = registerForm.querySelector('input[name="_token"]');
                    if (tokenInput) tokenInput.value = token;
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta) meta.setAttribute('content', token);
                }

                const formData = new FormData(registerForm);
                formData.set('registration_time', new Date().toISOString());
                const csrf = token || (document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    : '');

                Swal.fire({
                    title: 'Registering...',
                    text: 'Saving attendee and sending confirmation email.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                modal.style.display = 'none';

                fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.attendee) {
                        registerForm.reset();
                        document.getElementById('department').value = @json(strtoupper(trim(collect([$bookings->company, $bookings->department])->filter()->implode(' / '))));
                        Swal.fire({
                            icon: 'success',
                            title: 'Registered',
                            text: data.email_sent
                                ? 'Attendee registered and confirmation email sent.'
                                : 'Attendee registered, but the email could not be sent.',
                            confirmButtonColor: '#42CCC5'
                        }).then(() => window.location.reload());
                    } else {
                        modal.style.display = 'flex';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Registration failed.'
                        });
                    }
                })
                .catch(() => {
                    modal.style.display = 'flex';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Registration failed.'
                    });
                });
            };

            fetch('{{ route('csrf.token') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => sendRegistration(data.token))
            .catch(() => sendRegistration(null));
        });
    }
});
</script>
@endsection
