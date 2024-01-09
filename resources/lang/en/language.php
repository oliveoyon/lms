<?php

return [
    //Common
    "dashboard" => "Dashboard",
    "logout" => "Logout",
    "status" => "Status",
    "action" => "Action",
    "update" => "Update",
    "save" => "Save",
    "active" => "Active",
    "inactive" => "InActive",
    "home" => "Home",
    "contact" => "Contact",

    // Class Management
    "class_mgmt" => "Class Management",
    "class_list" => "Class List",
    "class_add" => "Add Class",
    "class_edit" => "Edit Class",
    "class_name" => "Class Name",
    "numeric" => "Numeric Value",
    "class_add_msg" => "Class Has Been Added Successfully",
    "class_edit_msg" => "Class Has Been Successfully Updated",
    "class_del_msg" => "Class Has Been Deleted From the List",

    "version_list" => "Version List",
    "version_add" => "Add Version",
    "version_edit" => "Edit Version",
    "version_name" => "Version Name",
    "version_add_msg" => "Version Has Been Added Successfully",
    "version_edit_msg" => "Version Has Been Successfully Updated",
    "version_del_msg" => "Version Has Been Deleted From the List",

    "section_list" => "Section List",
    "section_add" => "Add Section",
    "section_edit" => "Edit Section",
    "section_name" => "Section Name",
    "section_add_msg" => "Section Has Been Added Successfully",
    "section_edit_msg" => "Section Has Been Successfully Updated",
    "section_del_msg" => "Section Has Been Deleted From the List",
    "class_teacher" => "Class Teacher",
    "max_std" => "Maximum No. of Student",
    "select_class" => "Select a Class",
    "select_version" => "Select a Version",
    "select_class_teacher" => "Select a Class Teacher",

    "subject_list" => "Subject List",
    "subject_add" => "Add Subject",
    "subject_edit" => "Edit Subject",
    "subject_name" => "Subject Name",
    "subject_add_msg" => "Subject Has Been Added Successfully",
    "subject_edit_msg" => "Subject Has Been Successfully Updated",
    "subject_del_msg" => "Subject Has Been Deleted From the List",
    "subject_code" => "Subject Code",
    "academic_year" => "Academic Year",

    "class" => "Class",
    "section" => "Section",
    "subject" => "Subject",
    "version" => "Version",

    // Academic Fee Setup

    "aca_fee_setup" => "Academic Fee Setup",
    "aca_fee_head" => "Academic Fee Head",
    "aca_fee_group" => "Academic Fee Group",
    "fee_amount" => "Fee Amount",

    "fee_frequency" => "Fee Frequency",
    "fee_frequency_list" => "Fee Frequency List",
    "fee_frequency_add" => "Add Fee Frequency",
    "fee_frequency_edit" => "Edit Fee Frequency",
    "fee_frequency_name" => "Fee Frequency Name",
    "fee_frequency_add_msg" => "Fee Frequency Has Been Added Successfully",
    "fee_frequency_edit_msg" => "Fee Frequency Has Been Successfully Updated",
    "fee_frequency_del_msg" => "Fee Frequency Has Been Deleted From the List",
    "fee_frequency_cant_del_msg" => "Cannot delete the fee frequency as it is used in academic fee heads.",
    "no_of_installment" => "Number of Installments",
    "installment_period" => "Installment Period",

    // Academic Fee Head Management
    "fee_head" => "Fee Head Management",
    "fee_head_list" => "Academic Fee Head List",
    "fee_head_add" => "Add Academic Fee Head",
    "fee_head_edit" => "Edit Academic Fee Head",
    "fee_head_name" => "Academic Fee Head Name",
    "fee_head_description" => "Description",
    "fee_head_freq" => "Fee Frequency",
    "fee_head_no_of_installments" => "Number of Installments",
    "fee_head_add_msg" => "Academic Fee Head has been added successfully",
    "fee_head_edit_msg" => "Academic Fee Head has been successfully updated",
    "fee_head_del_msg" => "Academic Fee Head has been deleted from the list",
    "fee_head_cant_del" => "This academic fee head is used in fee groups and cannot be deleted.",
    "fee_head_not_found" => "Academic fee head not found",

    "fee_amount_group" => "Fee Amount Group",
    "fee_amount_group_list" => "Academic Fee Amount Group List",
    "fee_amount_group_add" => "Add Academic Fee Amount Group",
    "fee_amount_group_edit" => "Edit Academic Fee Amount Group",
    "fee_amount_group_name" => "Academic Fee Amount Group Name",
    "fee_amount_group_add_msg" => "Academic Fee Amount Group has been added successfully",
    "fee_amount_group_edit_msg" => "Academic Fee Amount Group has been successfully updated",
    "fee_amount_group_del_msg" => "Academic Fee Amount Group has been deleted from the list",

    // Manage Student

    "manage_student" => "Manage Student",
    "student_admission" => "Student Admission",
    "bulk_student_admission" => "Bulk Student Admission",
    "add_student" => "Add Student",
    "add_bulk_student" => "Buld Student Admission",
    "enroll_student" => "Enroll Students",
    "student_list" => "Student List",
    "promote_student" => "Promote Student",
    // from here
    "academic_details" => "Academic Details",
    "personal_details" => "Personal Details",
    "roll_no" => "Roll No",
    "admission_date" => "Admission Date",
    "std_category" => "Student Category",
    "fee_setup" => "Fee Setup",
    "student_full_name" => "Student Full Name (In English)",
    "student_full_name_bangla" => "Student Full Name (In Bangla)",
    "fathers_name" => "Father's Name",
    "mothers_name" => "Mother's Name",
    "students_phone" => "Student's Phone",
    "students_phone_alternative" => "Student's Phone Alternative",
    "date_of_birth" => "Date of Birth",
    "email" => "Email",
    "blood_group" => "Blood Group",
    "present_address" => "Present Address",
    "permanent_address" => "Permanent Address",
    "gender" => "Gender",
    "parents_guardian_details" => "Parents/Guardian Details",
    "fathers_occupation" => "Father's Occupation",
    "mothers_occupation" => "Mother's Occupation",
    "yearly_income" => "Yearly Income",
    "image_upload" => "Image Upload",
    "guardians_information" => "Guardian's Information (If the student does not live with parents)",
    "guardians_name" => "Guardian's Name",
    "relationship_with_student" => "Relationship with Student",
    "guardians_phone" => "Guardian's Phone",
    "guardians_address" => "Guardian's Address",
    "select" => "Select",
    "select_section" => "Select Section",
    "std_add_msg" => "Student Added Successfully",
    "std_enroll_msg" => "Student Enrolled Successfully",
    "std_edit_msg" => "Student Modified Successfully",
    "bulk_student_upload_instructions" => "To upload bulk students, Download CSV file from above button. Roll number should start from the suggested roll and then increment by one. The following fields are mandatory: Roll No, Student Name, Student Phone, Father Name, Mother Name, Date of Birth, Gender, Present Address, Permanent Address, Student Category. If you don't fill these columns in the CSV, it may create problems later. The Date column should be filled as 2023-12-03.",
    "upload_csv" => "Upload CSV",
    "student_name" => "Student's Name",
    "submit" => "Submit",
    "student_id" => "Student ID",
    "click_here" => "Click Here",
    "online_form" => "Online Application Form",
    "already_applied" => "If you already applied and want to print your Admission Form",
    "type_phone" => "Type your phone no..",
    "close" => "Close",
    "birth_certificate" => "Birth Certificate No.",
    // Exam Management
    "exam_mgmt" => "Exam Management",
    "exam_lists" => "Exam Lists",
    "exam_routine" => "Exam Routine",
    "admit_card" => "Admit Card",
    "result_grade" => "Result Grade",
    "result_entry" => "Result Entry",
    "mark_sheet" => "Mark Sheet",

    // Library Management
    "library_mgmt" => "Library Management",
    "book_category" => "Book Category",
    "book_lists" => "Book Lists",
    "issue_book" => "Issue Book",
    "return_book" => "Return Book",
    "library_reports" => "Library Reports",

    // Teacher Management
    "teacher_mgmt" => "Teacher Management",
    "teacher_lists" => "Teacher Lists",
    "assign_teacher_to_course" => "Assign Teacher to Course",

    // Routine Management
    "routine_mgmt" => "Routine Management",
    "class_period" => "Class Period",
    "create_class_period" => "Create Class Period",
    "view_class_period" => "View Class Period",
    "class_routine" => "Class Routine",

    // Notice/Events Management
    "notice_events_mgmt" => "Notice/Events Management",
    "notice_events" => "Notice/Events",

    // Transport Management
    "transport_mgmt" => "Transport Management",
    "stoppages" => "Stoppages",
    "vehicles_lists" => "Vehicles Lists",
    "routes_lists" => "Routes Lists",
    "assign_students" => "Assign Students",

    // Inventory Management
    "inventory_mgmt" => "Inventory Management",
    "product_categories" => "Product Categories",
    "units" => "Units",
    "stores" => "Stores",
    "vendors" => "Vendors",
    "products" => "Products",
    "product_purchases" => "Product Purchases",
    "recipients" => "Recipients",
    "issue_products" => "Issue Products",

    // Expenditure Management
    "expenditure_mgmt" => "Expenditure Management",
    "expenditure_types" => "Expenditure Types",
    "expenditure_bills" => "Expenditure Bills",
    "expenditure_reports" => "Expenditure Reports",

    // Certificate Management
    "certificate_mgmt" => "Certificate Management",
    "testimonial" => "Testimonial",
    "tabulation_sheet" => "Tabulation Sheet",
    "mark_sheet" => "Mark Sheet",
    "grade_report" => "Grade Report",

    // Hostel Management
    "hostel_mgmt" => "Hostel Management",
    "hostel_lists" => "Hostel Lists",
    "room_lists" => "Room Lists",
    "assign_students_hostel" => "Assign Students",

    // Assignments/Homeworks
    "assignments_homeworks" => "Assignments/Homeworks",

    // Settings
    "settings" => "Settings",
    "general_settings" => "General Settings",
    "fine_settings" => "Fine Settings",
    "email_settings" => "Email Settings",
    "sms_settings" => "SMS Settings",

    // Mailbox
    "mailbox" => "Mailbox",

    "book_category" => "Book Category",
    "book_category_list" => "Book Category List",
    "add_book_category" => "Add Book Category",
    "edit_book_category" => "Edit Book Category",
    "book_category_name" => "Book Category Name",
    "book_category_status" => "Book Category Status",
    "book_category_active" => "Active",
    "book_category_inactive" => "Inactive",

    "book_title" => "Title",
    "book_list" => "Book List",
    "author" => "Author",
    "isbn" => "ISBN",
    "edition" => "Edition",
    "publisher" => "Publisher",
    "shelf" => "Shelf",
    "position" => "Position",
    "book_purchase_date" => "Purchase Date",
    "cost" => "Cost",
    "no_of_copy" => "Number of Copies",
    "availability" => "Availability",
    "language" => "Language",
    "status" => "Status",
    "active" => "Active",
    "inactive" => "Inactive",
    "available" => "Available",
    "unavailable" => "Unavailable",
    "save" => "Save",
    "add_book" => "Add Book",
    "edit_book" => "Edit Book",
    "delete_book" => "Delete Book",
    "purchase_date" => "Purchase Date",

    "event_mgmt" => "Event Management",
    "event_list" => "Event List",
    "add_event" => "Add Event",
    "edit_event" => "Edit Event",
    "delete_event" => "Delete Event",
    "event_title" => "Event Title",
    "event_description" => "Event Description",
    "event_type" => "Event Type",
    "upload" => "Upload",
    "start_date" => "Start Date",
    "end_date" => "End Date",
    "url" => "URL",
    "color" => "Color",
    'event_add' => 'Add an Event',
    "event_status" => "Event Status",
    "event_add_msg" => "Event added successfully",
    "event_edit_msg" => "Event updated successfully",
    "event_del_msg" => "Event deleted successfully",

    "attendance_mgmt" => "Attendance Management",
    "attendance_input" => "Attendance Input",
    "attendance_edit" => "Attendance Edit",

    "school_title" => "School Title",
    "school_title_bangla" => "School Title (Bangla)",
    "school_short_name" => "School Short Name",
    "school_code" => "School Code",
    "school_eiin_no" => "School EIIN No",
    "school_email" => "School Email",
    "school_phone" => "School Phone",
    "school_phone1" => "School Phone 1",
    "school_phone2" => "School Phone 2",
    "school_fax" => "School Fax",
    "school_address" => "School Address",
    "school_country" => "School Country",
    "currency_sign" => "Currency Sign",
    "currency_name" => "Currency Name",
    "school_geocode" => "School Geocode",
    "school_facebook" => "School Facebook",
    "school_twitter" => "School Twitter",
    "school_google" => "School Google",
    "school_linkedin" => "School LinkedIn",
    "school_youtube" => "School YouTube",
    "school_copyrights" => "School Copyrights",
    "school_logo" => "School Logo",
    "currency" => "Currency",
    "set_status" => "Set Status",
    "timezone" => "Timezone",
    "enable_notifications" => "Enable Notifications",

    "fees_collection" => "Fees Collection",
    "collect_fee" => "Collect Fee",
    "custom_fee_generate" => "Custom Fee Generate",

];
