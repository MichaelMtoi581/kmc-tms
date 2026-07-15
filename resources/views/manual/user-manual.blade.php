<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TMIS User Manual</title>
    <style>
        @page { margin: 20mm 18mm 25mm 18mm; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #2c2c2c;
        }

        /* ===== Cover Page ===== */
        .cover-page {
            text-align: center;
            padding-top: 100px;
        }
        .cover-page .kmc-logo {
            width: 90px;
            height: 90px;
            margin-bottom: 20px;
        }
        .cover-page h1 {
            font-size: 26pt;
            color: #1a3a5c;
            margin-bottom: 6px;
        }
        .cover-page h2 {
            font-size: 16pt;
            color: #2c6faa;
            font-weight: normal;
            margin-bottom: 30px;
        }
        .cover-page .meta {
            font-size: 11pt;
            color: #555;
            margin-top: 40px;
            line-height: 2;
        }
        .cover-page .meta strong {
            color: #1a3a5c;
        }
        .cover-line {
            width: 200px;
            height: 3px;
            background: #2c6faa;
            margin: 25px auto;
        }

        /* ===== Page Header ===== */
        #header {
            position: fixed;
            top: -15mm;
            left: 0;
            right: 0;
            height: 12mm;
            border-bottom: 2px solid #2c6faa;
            font-size: 8pt;
            color: #888;
            padding-bottom: 3px;
        }
        #header .left { float: left; }
        #header .right { float: right; }

        /* ===== Page Footer ===== */
        #footer {
            position: fixed;
            bottom: -18mm;
            left: 0;
            right: 0;
            height: 14mm;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #999;
            text-align: center;
            padding-top: 5px;
        }
        #footer .page-number:before { content: "Page " counter(page); }

        /* ===== TOC ===== */
        .toc-page h2 {
            font-size: 18pt;
            color: #1a3a5c;
            border-bottom: 2px solid #2c6faa;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .toc-list { list-style: none; padding: 0; margin: 0; }
        .toc-list li {
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
            font-size: 10pt;
        }
        .toc-list li span.num {
            display: inline-block;
            width: 32px;
            font-weight: bold;
            color: #2c6faa;
        }

        /* ===== Section Headings ===== */
        h2.section {
            font-size: 16pt;
            color: #1a3a5c;
            border-bottom: 3px solid #2c6faa;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        h3 {
            font-size: 12pt;
            color: #2c6faa;
            margin-top: 20px;
        }
        h4 {
            font-size: 10pt;
            color: #444;
            margin-top: 12px;
        }

        /* ===== Tables ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px 7px;
            text-align: left;
        }
        th {
            background-color: #2c6faa;
            color: #fff;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        tr:nth-child(even) td {
            background-color: #f5f8fc;
        }

        /* ===== Callout boxes ===== */
        .note {
            background: #e8f0fa;
            border-left: 4px solid #2c6faa;
            padding: 8px 12px;
            margin: 12px 0;
            font-size: 9pt;
        }
        .tip {
            background: #e6f7e6;
            border-left: 4px solid #28a745;
            padding: 8px 12px;
            margin: 12px 0;
            font-size: 9pt;
        }
        .warning {
            background: #fff8e6;
            border-left: 4px solid #ffc107;
            padding: 8px 12px;
            margin: 12px 0;
            font-size: 9pt;
        }

        /* ===== Steps ===== */
        .step { margin: 4px 0 4px 18px; }
        .step strong { color: #1a3a5c; }

        /* ===== Print / Page Break ===== */
        .page-break { page-break-before: always; }

        ul, ol { margin: 4px 0; padding-left: 20px; }
        li { margin: 2px 0; }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-blue { background: #2c6faa; color: #fff; }
        .badge-green { background: #28a745; color: #fff; }
        .badge-orange { background: #fd7e14; color: #fff; }
        .badge-red { background: #dc3545; color: #fff; }

        strong { color: #1a3a5c; }
        code { background: #f0f0f0; padding: 1px 4px; border-radius: 2px; font-size: 8pt; }
    </style>
</head>
<body>

<!-- ============================================================ -->
<!-- COVER PAGE -->
<!-- ============================================================ -->
<div class="cover-page">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Coat_of_arms_of_Tanzania.svg/120px-Coat_of_arms_of_Tanzania.svg.png"
         alt="KMC Logo" class="kmc-logo" style="width:90px;height:90px;">

    <h1>Training Management<br>Information System</h1>
    <h2>USER MANUAL</h2>

    <div class="cover-line"></div>

    <div class="meta">
        <strong>System:</strong> TMIS &mdash; Training Management Information System<br>
        <strong>Version:</strong> 1.0<br>
        <strong>Organization:</strong> Kinondoni Municipal Council (KMC)<br>
        <strong>Prepared:</strong> July 2026
    </div>

    <div style="margin-top:60px; font-size:9pt; color:#aaa;">
        This document is intended for authorized users of the TMIS system.<br>
        Unauthorized distribution is prohibited.
    </div>
</div>

<!-- ============================================================ -->
<!-- TABLE OF CONTENTS -->
<!-- ============================================================ -->
<div class="page-break"></div>

<div class="toc-page">
    <h2>Table of Contents</h2>
    <ol class="toc-list">
        <li><span class="num">1.</span> Introduction</li>
        <li><span class="num">2.</span> System Requirements &amp; Access</li>
        <li><span class="num">3.</span> Logging In &amp; Authentication</li>
        <li><span class="num">4.</span> Dashboard Overview</li>
        <li><span class="num">5.</span> User Roles &amp; Permissions</li>
        <li><span class="num">6.</span> Staff Management</li>
        <li><span class="num">7.</span> Organization Settings (Admin)</li>
        <li><span class="num">8.</span> Planned Trainings</li>
        <li><span class="num">9.</span> Unplanned Trainings</li>
        <li><span class="num">10.</span> Importing Trainings from Excel</li>
        <li><span class="num">11.</span> Reports Module</li>
        <li><span class="num">12.</span> User Management (Admin)</li>
        <li><span class="num">13.</span> Audit Log (Admin)</li>
        <li><span class="num">14.</span> Dark Mode</li>
        <li><span class="num">15.</span> Profile Settings</li>
        <li><span class="num">16.</span> Troubleshooting &amp; FAQ</li>
    </ol>
</div>

<!-- ============================================================ -->
<!-- 1. INTRODUCTION -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">1. Introduction</h2>

<p>The <strong>Training Management Information System (TMIS)</strong> is a web-based application developed for <strong>Kinondoni Municipal Council (KMC)</strong> to streamline the management of staff training records. The system enables the Human Resources department to record, track, and report on both planned and unplanned training activities undertaken by council employees.</p>

<h3>1.1 Purpose of the System</h3>
<ul>
    <li>Centralize all staff training records in a single digital repository.</li>
    <li>Differentiate between <em>planned</em> (scheduled in advance) and <em>unplanned</em> (ad-hoc/emergency) trainings.</li>
    <li>Automatically calculate training duration (Short / Long).</li>
    <li>Provide rich reporting with Excel, PDF, and Print export capabilities.</li>
    <li>Ensure data integrity through role-based access control.</li>
</ul>

<h3>1.2 Key Features</h3>
<ul>
    <li><strong>Role-Based Access:</strong> Two user roles &mdash; Admin (full access) and HR Officer (operational access).</li>
    <li><strong>Training Records:</strong> Comprehensive 15-field form covering all aspects of a training event.</li>
    <li><strong>Excel Import:</strong> Bulk upload trainings using a downloadable template.</li>
    <li><strong>Auto-Completion:</strong> Training status automatically updates to "Completed" when the end date passes.</li>
    <li><strong>Duration Calculation:</strong> Automatically classifies trainings as Short (&lt; 6 months) or Long (&ge; 6 months).</li>
    <li><strong>Dark Mode:</strong> Toggle between light and dark display themes.</li>
    <li><strong>Audit Trail:</strong> All create, update, delete, and import operations are logged.</li>
    <li><strong>7 Report Types:</strong> Summary, Department, Staff, Financial Year, Cost, Status, and Duration reports.</li>
</ul>


<!-- ============================================================ -->
<!-- 2. SYSTEM REQUIREMENTS & ACCESS -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">2. System Requirements &amp; Access</h2>

<h3>2.1 Technical Requirements</h3>
<table>
    <tr><th>Component</th><th>Requirement</th></tr>
    <tr><td>Web Browser</td><td>Google Chrome 90+, Mozilla Firefox 88+, Microsoft Edge 90+</td></tr>
    <tr><td>Screen Resolution</td><td>1280 x 768 or higher recommended</td></tr>
    <tr><td>Internet Connection</td><td>Local network (Laragon) or intranet</td></tr>
    <tr><td>PDF Viewer</td><td>Required for viewing exported PDF reports</td></tr>
</table>

<h3>2.2 Accessing the System</h3>
<p>Open your web browser and navigate to the TMIS URL provided by your system administrator. The default local URL is:</p>
<div class="note">
    <strong>http://kmc-tms.test</strong> (or the URL assigned by your IT department)
</div>


<!-- ============================================================ -->
<!-- 3. LOGGING IN & AUTHENTICATION -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">3. Logging In &amp; Authentication</h2>

<h3>3.1 Login Page</h3>
<ol>
    <li>Open your browser and go to the TMIS URL. You will be redirected to the <strong>Login Page</strong>.</li>
    <li>Enter your registered <strong>Email Address</strong>.</li>
    <li>Enter your <strong>Password</strong>.</li>
    <li>Click the <strong>Sign In</strong> button.</li>
</ol>

<div class="tip">
    <strong>Tip:</strong> Click the <em>eye icon</em> next to the password field to toggle password visibility and verify you have typed it correctly.
</div>

<div class="note">
    <strong>Default Credentials (for testing):</strong><br>
    Admin: admin@kmc.go.tz / password<br>
    HR Officer: hr@kmc.go.tz / password
</div>

<h3>3.2 After Login</h3>
<p>Upon successful login, you will land on the <strong>Dashboard</strong>. The left sidebar shows all modules you have access to based on your role.</p>

<h3>3.3 Logging Out</h3>
<p>Click your name or avatar in the top-right corner of the navigation bar, then select <strong>Logout</strong>.</p>


<!-- ============================================================ -->
<!-- 4. DASHBOARD OVERVIEW -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">4. Dashboard Overview</h2>

<p>The Dashboard serves as the home page and provides a high-level summary of the system's data through interactive stat cards and charts.</p>

<h3>4.1 Statistic Cards</h3>
<table>
    <tr><th>Card</th><th>Description</th></tr>
    <tr><td>Total Staff</td><td>Total number of staff members recorded in the system.</td></tr>
    <tr><td>Total Trainings</td><td>Combined count of all Planned and Unplanned trainings.</td></tr>
    <tr><td>This Month</td><td>Number of trainings occurring in the current month.</td></tr>
    <tr><td>Completion Rate</td><td>Percentage of trainings marked as Completed.</td></tr>
    <tr><td>Staff Trained</td><td>Number of unique staff members who have attended at least one training.</td></tr>
    <tr><td>Active Trainings</td><td>Trainings currently in "Ongoing" status.</td></tr>
    <tr><td>Departments</td><td>Total number of departments (visible to Admin only).</td></tr>
</table>

<h3>4.2 Charts &amp; Tables</h3>
<ul>
    <li><strong>Training Status Distribution:</strong> A bar chart showing counts by status (Planned, Ongoing, Completed, Cancelled).</li>
    <li><strong>Training by Category:</strong> A doughnut chart breaking down trainings by category.</li>
    <li><strong>Top 5 Departments:</strong> Departments with the highest number of trainings.</li>
    <li><strong>Upcoming Trainings:</strong> A table listing trainings scheduled for the next 30 days.</li>
    <li><strong>Recent Trainings:</strong> The 10 most recently created training records.</li>
</ul>


<!-- ============================================================ -->
<!-- 5. USER ROLES & PERMISSIONS -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">5. User Roles &amp; Permissions</h2>

<p>TMIS has two user roles. The sidebar menu and available features adjust automatically based on your role.</p>

<table>
    <tr><th>Feature / Module</th><th>Admin</th><th>HR Officer</th></tr>
    <tr><td>Dashboard</td><td><span class="badge badge-green">Full</span></td><td><span class="badge badge-green">Full</span></td></tr>
    <tr><td>Staff Management</td><td><span class="badge badge-green">Full</span></td><td><span class="badge badge-green">Full</span></td></tr>
    <tr><td>Planned Trainings</td><td><span class="badge badge-green">Full</span></td><td><span class="badge badge-green">Full</span></td></tr>
    <tr><td>Unplanned Trainings</td><td><span class="badge badge-green">Full</span></td><td><span class="badge badge-green">Full</span></td></tr>
    <tr><td>Reports</td><td><span class="badge badge-green">Full</span></td><td><span class="badge badge-green">Full</span></td></tr>
    <tr><td>Departments</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-orange">Use in dropdowns</span></td></tr>
    <tr><td>Financial Years</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-orange">Use in dropdowns</span></td></tr>
    <tr><td>Training Categories</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-orange">Use in dropdowns</span></td></tr>
    <tr><td>Training Institutions</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-orange">Use in dropdowns</span></td></tr>
    <tr><td>Funding Sources</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-orange">Use in dropdowns</span></td></tr>
    <tr><td>User Management</td><td><span class="badge badge-green">Manage</span></td><td><span class="badge badge-red">Not Available</span></td></tr>
    <tr><td>Audit Log</td><td><span class="badge badge-green">View</span></td><td><span class="badge badge-red">Not Available</span></td></tr>
</table>

<div class="note">
    <strong>Note:</strong> HR Officers can select from existing Departments, Financial Years, Categories, Institutions, and Funding Sources when creating training records, but cannot add, edit, or delete them.
</div>


<!-- ============================================================ -->
<!-- 6. STAFF MANAGEMENT -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">6. Staff Management</h2>

<p>The <strong>Staff</strong> module is used to manage the list of council employees who can be assigned to trainings.</p>

<h3>6.1 Viewing Staff</h3>
<ol>
    <li>Click <strong>Staff</strong> in the sidebar under the TRAININGS header.</li>
    <li>The page displays a DataTable with all staff members, showing: Check Number, Full Name, Gender, Designation, Education Level, and Department.</li>
    <li>Use the search box to filter records. Use the <em>per page</em> dropdown to change how many rows are displayed.</li>
</ol>

<h3>6.2 Adding a New Staff Member</h3>
<ol>
    <li>Click the <strong>Add New</strong> button at the top of the Staff list page.</li>
    <li>Fill in the required fields:
        <ul>
            <li><strong>Check Number</strong> &mdash; unique staff identifier (e.g., KMC001)</li>
            <li><strong>First Name, Middle Name, Last Name</strong></li>
            <li><strong>Gender</strong> &mdash; select from dropdown</li>
            <li><strong>Date of Birth</strong></li>
            <li><strong>Designation</strong> &mdash; job title</li>
            <li><strong>Education Level</strong> &mdash; highest education attained</li>
            <li><strong>Department</strong> &mdash; select from existing departments</li>
        </ul>
    </li>
    <li>Click <strong>Save</strong>.</li>
</ol>

<h3>6.3 Editing a Staff Member</h3>
<ol>
    <li>Click the <strong>Edit</strong> button (pencil icon) next to the staff member in the table.</li>
    <li>Modify the fields as needed.</li>
    <li>Click <strong>Update</strong>.</li>
</ol>

<h3>6.4 Deleting a Staff Member</h3>
<ol>
    <li>Click the <strong>Delete</strong> button (trash icon).</li>
    <li>Confirm the deletion in the pop-up dialog.</li>
</ol>

<div class="warning">
    <strong>Warning:</strong> If a staff member has existing training records, the system will prevent deletion to protect data integrity. You must reassign or delete their training records first.
</div>


<!-- ============================================================ -->
<!-- 7. ORGANIZATION SETTINGS (ADMIN) -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">7. Organization Settings <span style="font-size:9pt;color:#888;">(Admin Only)</span></h2>

<p>These modules are accessible only to Admin users and appear under the <strong>ORGANIZATION</strong> header in the sidebar. They define the reference data used throughout the system.</p>

<h3>7.1 Departments</h3>
<p>Manage council departments. Each department has a <strong>Name</strong> field. Departments are assigned to staff members and used to filter reports.</p>

<h3>7.2 Financial Years</h3>
<p>Manage financial years (e.g., 2025/2026). Each year is identified by a unique <strong>Year Name</strong>. Trainings are linked to a financial year for reporting purposes.</p>

<h3>7.3 Training Categories</h3>
<p>Define categories for trainings (e.g., "Short Course", "Workshop", "Seminar", "Conference"). Each category has a <strong>Name</strong> and optional <strong>Description</strong>.</p>

<h3>7.4 Training Institutions</h3>
<p>Manage institutions that provide training (e.g., "Dar es Salaam Institute of Technology"). Fields: <strong>Name</strong>, <strong>Location</strong>, and <strong>Contact</strong>.</p>

<h3>7.5 Funding Sources</h3>
<p>Manage sources of training funding (e.g., "Council Budget", "Donor Funded"). Fields: <strong>Name</strong> and optional <strong>Description</strong>.</p>

<div class="tip">
    <strong>Tip:</strong> All five Organization modules share the same interface: a DataTable listing records, plus Add New, Edit, and Delete buttons. Use the search box to quickly find specific entries.
</div>


<!-- ============================================================ -->
<!-- 8. PLANNED TRAININGS -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">8. Planned Trainings</h2>

<p><strong>Planned Trainings</strong> are training activities that are scheduled in advance as part of the council's annual training plan.</p>

<h3>8.1 Viewing Planned Trainings</h3>
<ol>
    <li>Click <strong>Planned Trainings</strong> in the sidebar under the TRAININGS header.</li>
    <li>The page shows a DataTable with all planned training records and key columns.</li>
    <li>Use the <strong>Financial Year</strong> and <strong>Department</strong> filters at the top to narrow results.</li>
</ol>

<h3>8.2 Creating a Planned Training</h3>
<ol>
    <li>Click <strong>Add New</strong> on the Planned Trainings page.</li>
    <li>Fill in the form:
        <table>
            <tr><th>Field</th><th>Required</th><th>Description</th></tr>
            <tr><td>Course Title</td><td>Yes</td><td>Name of the training course</td></tr>
            <tr><td>Staff</td><td>Yes</td><td>Select staff member from dropdown</td></tr>
            <tr><td>Department</td><td>Yes</td><td>Auto-filled from staff or select manually</td></tr>
            <tr><td>Financial Year</td><td>Yes</td><td>Select from list (e.g., 2025/2026)</td></tr>
            <tr><td>Category</td><td>Yes</td><td>Training category</td></tr>
            <tr><td>Institution</td><td>No</td><td>Training provider / institution</td></tr>
            <tr><td>Funding Source</td><td>No</td><td>Source of funding</td></tr>
            <tr><td>Start Date</td><td>No</td><td>Training start date</td></tr>
            <tr><td>End Date</td><td>No</td><td>Training end date</td></tr>
            <tr><td>Duration</td><td>Auto</td><td>Calculated: Short (&lt; 6mo) or Long (&ge; 6mo)</td></tr>
            <tr><td>Venue</td><td>No</td><td>Location of the training</td></tr>
            <tr><td>Cost (TZS)</td><td>No</td><td>Cost amount in Tanzanian Shillings</td></tr>
            <tr><td>Status</td><td>Yes</td><td>Planned, Ongoing, Completed, or Cancelled</td></tr>
            <tr><td>Description</td><td>No</td><td>Purpose or notes about the training</td></tr>
            <tr><td>Remarks</td><td>No</td><td>Additional comments</td></tr>
        </table>
    </li>
    <li>Click <strong>Save</strong>.</li>
</ol>

<div class="note">
    <strong>Auto-Calculation:</strong> The <strong>Duration</strong> field is automatically calculated based on the difference between Start Date and End Date. If the difference is less than 6 months, it shows "Short"; otherwise "Long". The badge preview updates live as you select dates.
</div>

<div class="note">
    <strong>Auto-Completion:</strong> If the End Date is in the past and the status is not "Cancelled", the system will automatically set the status to "Completed" when the record is saved.
</div>

<h3>8.3 Editing a Planned Training</h3>
<ol>
    <li>Click the <strong>Edit</strong> button (pencil icon) next to the record.</li>
    <li>Modify the fields and click <strong>Update</strong>.</li>
</ol>

<h3>8.4 Viewing Details</h3>
<p>Click the <strong>Show</strong> button (eye icon) to view the full details of a training record in a dedicated read-only page.</p>

<h3>8.5 Deleting a Planned Training</h3>
<ol>
    <li>Click the <strong>Delete</strong> button (trash icon).</li>
    <li>Confirm the deletion in the pop-up dialog.</li>
</ol>


<!-- ============================================================ -->
<!-- 9. UNPLANNED TRAININGS -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">9. Unplanned Trainings</h2>

<p><strong>Unplanned Trainings</strong> are ad-hoc or emergency training activities that were not included in the annual training plan. The interface is identical to Planned Trainings.</p>

<h3>9.1 Key Difference</h3>
<p>The only difference between Planned and Unplanned Trainings is the <strong>Source</strong> field, which is preset to "Unplanned" for records created in this module. All form fields, validation rules, and behaviors (including auto-duration, auto-completion, and duplicate prevention) are the same.</p>

<div class="tip">
    <strong>Tip:</strong> Use <strong>Planned Trainings</strong> for trainings scheduled in the annual plan. Use <strong>Unplanned Trainings</strong> for trainings that arise unexpectedly (e.g., an urgent workshop called by the ministry).
</div>


<!-- ============================================================ -->
<!-- 10. IMPORTING TRAININGS FROM EXCEL -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">10. Importing Trainings from Excel</h2>

<p>Both Planned and Unplanned Trainings support bulk import from Excel (.xlsx, .xls) or CSV files. This is useful for migrating data from legacy systems or adding many records at once.</p>

<h3>10.1 Downloading the Template</h3>
<ol>
    <li>Navigate to the <strong>Import</strong> page (click <strong>Import</strong> on the Planned or Unplanned Trainings list page).</li>
    <li>Click the <strong>Download Template</strong> button. An .xlsx file will be downloaded.</li>
    <li>Open the template in Excel. It contains the correct column headers and one sample row.</li>
</ol>

<h3>10.2 Preparing Your Data</h3>
<table>
    <tr><th>Column</th><th>Required</th><th>Notes</th></tr>
    <tr><td>course_title</td><td>Yes</td><td>Name of the training (max 255 characters)</td></tr>
    <tr><td>check_number</td><td>Yes</td><td>Must match an existing staff record</td></tr>
    <tr><td>department</td><td>Yes</td><td>Must match an existing department name</td></tr>
    <tr><td>financial_year</td><td>Yes</td><td>Must match an existing year name (e.g., 2025/2026)</td></tr>
    <tr><td>category</td><td>Yes</td><td>Must match an existing category name</td></tr>
    <tr><td>institution</td><td>No</td><td>Must match if provided</td></tr>
    <tr><td>funding_source</td><td>No</td><td>Must match if provided</td></tr>
    <tr><td>start_date</td><td>No</td><td>dd/mm/yyyy or Excel date format</td></tr>
    <tr><td>end_date</td><td>No</td><td>dd/mm/yyyy or Excel date format</td></tr>
    <tr><td>venue</td><td>No</td><td>Training location</td></tr>
    <tr><td>cost</td><td>No</td><td>Numeric value, max 99,999,999 TZS</td></tr>
    <tr><td>description</td><td>No</td><td>Free text</td></tr>
    <tr><td>remarks</td><td>No</td><td>Free text</td></tr>
</table>

<h3>10.3 Importing the File</h3>
<ol>
    <li>On the Import page, click <strong>Choose File</strong> and select your prepared Excel file.</li>
    <li>Click the <strong>Import</strong> button.</li>
    <li>The system will validate each row and show a summary:
        <ul>
            <li>Number of rows successfully imported</li>
            <li>Number of duplicate rows skipped (same staff + course + financial year)</li>
            <li>Number of rows with errors (e.g., invalid check number)</li>
        </ul>
    </li>
</ol>

<div class="warning">
    <strong>Note:</strong> HTML tags in free-text fields are automatically stripped for security. Cost values are sanitized and validated.
</div>


<!-- ============================================================ -->
<!-- 11. REPORTS MODULE -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">11. Reports Module</h2>

<p>The Reports module provides seven different report types to analyze training data. Click <strong>Reports</strong> in the sidebar to see the list.</p>

<h3>11.1 Report Types</h3>

<table>
    <tr><th>Report</th><th>Description</th></tr>
    <tr><td>Training Summary</td><td>Overall stats: total trainings, planned vs unplanned, cost totals, completion rate</td></tr>
    <tr><td>By Department</td><td>Breakdown of trainings per department with counts and costs</td></tr>
    <tr><td>By Staff</td><td>Select any staff member to view their complete training history with stat cards</td></tr>
    <tr><td>By Financial Year</td><td>Analysis of trainings grouped by financial year</td></tr>
    <tr><td>Cost Analysis</td><td>Cost breakdown by funding source, total/average/max costs with filters</td></tr>
    <tr><td>By Status</td><td>Counts of trainings for each status with visual badges</td></tr>
    <tr><td>By Duration</td><td>Short vs Long training analysis with stat cards and filters</td></tr>
</table>

<h3>11.2 Exporting Reports</h3>
<p>Each report page includes export buttons:</p>
<ul>
    <li><span class="badge badge-green">Excel</span> &mdash; Downloads data as .xlsx file</li>
    <li><span class="badge badge-red">PDF</span> &mdash; Downloads data as a PDF document</li>
    <li><span class="badge badge-orange">Print</span> &mdash; Opens the browser print dialog to print or save as PDF</li>
</ul>

<h3>11.3 Staff Report (Detail View)</h3>
<ol>
    <li>Click <strong>Reports</strong> then <strong>By Staff</strong>.</li>
    <li>A table of all staff members is displayed. Use search/filter to find a specific staff member.</li>
    <li>Click <strong>View Trainings</strong> on any row to open the staff's dedicated training history page.</li>
    <li>The detail page shows:
        <ul>
            <li>Stat cards (Total Trainings, Planned, Unplanned, Total Cost)</li>
            <li>Full training history table with its own search</li>
            <li>Staff details card (Name, Check No, Department, Designation)</li>
            <li>Export buttons for Excel, PDF, and Print</li>
        </ul>
    </li>
    <li>Click <strong>Back to Staff List</strong> to return to the staff table.</li>
</ol>


<!-- ============================================================ -->
<!-- 12. USER MANAGEMENT (ADMIN) -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">12. User Management <span style="font-size:9pt;color:#888;">(Admin Only)</span></h2>

<p>The <strong>User Management</strong> module allows Admin users to manage system users. Access it from the sidebar under the ORGANIZATION header.</p>

<h3>12.1 Viewing Users</h3>
<p>The user list displays all registered users with their Name, Email, and Role.</p>

<h3>12.2 Creating a New User</h3>
<ol>
    <li>Click <strong>Add New</strong> on the User Management page.</li>
    <li>Fill in:
        <ul>
            <li><strong>Full Name</strong></li>
            <li><strong>Email</strong> &mdash; used for login</li>
            <li><strong>Role</strong> &mdash; select "HR" (Admin role cannot be assigned from the UI)</li>
            <li><strong>Password</strong> &mdash; minimum 8 characters (use the eye icon to verify)</li>
            <li><strong>Confirm Password</strong></li>
        </ul>
    </li>
    <li>Click <strong>Save</strong>.</li>
</ol>

<h3>12.3 Editing a User</h3>
<p>You can update a user's Name, Email, and Role. To reset their password, enter a new password in the "New Password" field (leave blank to keep the current password).</p>

<h3>12.4 Deleting a User</h3>
<p>Click the <strong>Delete</strong> button and confirm. Deleting a user is permanent.</p>


<!-- ============================================================ -->
<!-- 13. AUDIT LOG (ADMIN) -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">13. Audit Log <span style="font-size:9pt;color:#888;">(Admin Only)</span></h2>

<p>The <strong>Audit Log</strong> provides a chronological record of all significant actions performed in the system. Access it from the sidebar under the ORGANIZATION header.</p>

<h3>13.1 What Is Logged</h3>
<table>
    <tr><th>Action</th><th>Logged When</th></tr>
    <tr><td><span class="badge badge-green">Created</span></td><td>A training record or staff member is added via the create form</td></tr>
    <tr><td><span class="badge badge-blue">Updated</span></td><td>A training record or staff member is edited</td></tr>
    <tr><td><span class="badge badge-red">Deleted</span></td><td>A training record or staff member is deleted</td></tr>
    <tr><td><span class="badge badge-orange">Imported</span></td><td>Trainings are bulk-imported from Excel</td></tr>
</table>

<h3>13.2 Viewing the Audit Log</h3>
<ul>
    <li>The page shows each entry with: User, Action (color-coded badge), Description, and Date.</li>
    <li>Use the search box to filter by user name, action type, or description text.</li>
    <li>Entries are sorted by date (newest first).</li>
</ul>


<!-- ============================================================ -->
<!-- 14. DARK MODE -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">14. Dark Mode</h2>

<p>TMIS includes a built-in dark mode feature to reduce eye strain in low-light environments.</p>

<h3>14.1 Toggling Dark Mode</h3>
<ol>
    <li>Look for the <strong>moon/sun icon</strong> in the top navigation bar (navbar).</li>
    <li>Click the icon to toggle between light and dark themes.</li>
    <li>The system remembers your preference for future sessions.</li>
</ol>

<div class="tip">
    <strong>Tip:</strong> Moon icon = switch to dark mode. Sun icon = switch to light mode.
</div>


<!-- ============================================================ -->
<!-- 15. PROFILE SETTINGS -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">15. Profile Settings</h2>

<p>Users can update their profile information and change their password from the Profile page.</p>

<ol>
    <li>Click your name in the top-right corner of the navigation bar.</li>
    <li>Select <strong>Profile</strong> from the dropdown menu.</li>
    <li>On the Profile page, you can:
        <ul>
            <li><strong>Update Profile Information:</strong> Change your name and email address.</li>
            <li><strong>Update Password:</strong> Enter your current password, then the new password twice.</li>
            <li><strong>Delete Account:</strong> Permanently remove your account (requires password confirmation).</li>
        </ul>
    </li>
    <li>Click <strong>Save</strong> to apply changes.</li>
</ol>


<!-- ============================================================ -->
<!-- 16. TROUBLESHOOTING & FAQ -->
<!-- ============================================================ -->
<div class="page-break"></div>
<h2 class="section">16. Troubleshooting &amp; FAQ</h2>

<h3>16.1 I forgot my password</h3>
<p>The password reset feature is currently being configured. Please contact your system administrator to reset your password.</p>

<h3>16.2 I cannot delete a staff member or training record</h3>
<p>This usually happens when the record has dependencies. For example, a staff member cannot be deleted if they have training records. Remove the associated records first, or contact an administrator.</p>

<h3>16.3 The page looks broken or styles are missing</h3>
<ul>
    <li>Try refreshing the page (Ctrl + F5).</li>
    <li>Clear your browser cache.</li>
    <li>Ensure you are using a supported browser (Chrome 90+, Firefox 88+, Edge 90+).</li>
</ul>

<h3>16.4 The Dashboard shows 0 trainings even though I added some</h3>
<p>Check that trainings are linked to the correct Financial Year. The Dashboard aggregates data across all years. If the issue persists, contact your system administrator.</p>

<h3>16.5 My Excel import failed with validation errors</h3>
<ul>
    <li>Download the template again and compare your column headers.</li>
    <li>Ensure all "required" columns have data in every row.</li>
    <li>Verify that check numbers, department names, etc. exist in the system.</li>
    <li>Check that cost values are numeric and do not exceed 99,999,999.</li>
</ul>

<h3>16.6 I see "403 Forbidden" on some pages</h3>
<p>You do not have the required role to access that page. Only Admin users can manage Organization Settings, Users, and the Audit Log. If you believe this is an error, contact your system administrator.</p>

<h3>16.7 How do I print a report?</h3>
<p>Each report page has a <strong>Print</strong> button that opens your browser's print dialog. You can also export to PDF for a cleaner document.</p>

<h3>16.8 Who do I contact for support?</h3>
<p>For technical support or questions about the system, please contact the <strong>ICT Department</strong> at Kinondoni Municipal Council.</p>

<!-- ============================================================ -->
<!-- FOOTER -->
<!-- ============================================================ -->
<div style="margin-top:40px; padding-top:15px; border-top:2px solid #2c6faa; text-align:center; font-size:8pt; color:#999;">
    <strong>TMIS &mdash; Training Management Information System</strong><br>
    Kinondoni Municipal Council &bull; July 2026 &bull; Version 1.0
</div>

</body>
</html>