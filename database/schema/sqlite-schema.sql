CREATE TABLE "academic_periods"(
  "id" integer not null primary key autoincrement,
  "start_date" date NOT NULL,
  "end_date" date NOT NULL,
  "academic_term_id" bigint(20) NOT NULL,
  "year_label" varchar(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "head_of_student_services" varchar(100) DEFAULT NULL,
  "branch_director" varchar(100) DEFAULT NULL,
  CONSTRAINT "academic_periods_academic_term_id_foreign" FOREIGN KEY("academic_term_id") REFERENCES "academic_terms"("id")
);
CREATE TABLE "academic_terms"(
  "id" integer not null primary key autoincrement,
  "system" varchar(20) NOT NULL,
  "term_number" tinyint(3) NOT NULL,
  "label" varchar(30) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "chk_system" CHECK("system" in('semester','trimester','quarter'))
);
CREATE TABLE "accom_reports"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "approved_ar_filepath" varchar(255) DEFAULT NULL,
  "current_step" varchar(20) NOT NULL,
  "status" varchar(20) NOT NULL,
  "comments" text DEFAULT NULL,
  "approved_at" timestamp NULL DEFAULT NULL,
  "returned_at" timestamp NULL DEFAULT NULL,
  "submitted_at" timestamp NULL DEFAULT NULL,
  "president_user_id" bigint(20) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "filepath" varchar(255) DEFAULT NULL,
  "file_updated" tinyint(1) DEFAULT NULL,
  "file_updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "accom_reports_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id"),
  CONSTRAINT "accom_reports_president_user_id_foreign" FOREIGN KEY("president_user_id") REFERENCES "users"("id"),
  CONSTRAINT "chk_current_step" CHECK("current_step" in('officers','president','adviser')),
  CONSTRAINT "chk_status" CHECK("status" in('draft','pending','returned','approved'))
);
CREATE TABLE "activity_logs"(
  "id" integer not null primary key autoincrement,
  "user_id" bigint(20) NOT NULL,
  "category" varchar(255) NOT NULL,
  "action" varchar(255) NOT NULL,
  "item_id" bigint(20) NOT NULL,
  "date" datetime NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "activity_logs_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "announcements"(
  "id" integer not null primary key autoincrement,
  "title" varchar(255) NOT NULL,
  "introduction" varchar(255) NOT NULL,
  "message" text NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "announcements_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "audit_trail"(
  "id" integer not null primary key autoincrement,
  "action" varchar(10) NOT NULL,
  "table_name" varchar(100) NOT NULL,
  "column_names" longtext DEFAULT NULL,
  "primary_key" bigint(20) NOT NULL,
  "request_id" char(26) DEFAULT NULL,
  "request_ip" varchar(45) DEFAULT NULL,
  "request_url" text DEFAULT NULL,
  "request_method" varchar(10) DEFAULT NULL,
  "request_time" timestamp NULL DEFAULT NULL,
  "user_id" bigint(20) DEFAULT NULL,
  "user_agent" text DEFAULT NULL,
  "session_id" varchar(255) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "chk_action" CHECK("action" in('insert','update','delete')),
  CONSTRAINT "chk_request_method" CHECK("request_method" in('get','post','put','patch','delete','options'))
);
CREATE TABLE "cache"(
  "key" varchar(255) NOT NULL,
  "value" mediumtext NOT NULL,
  "expiration" int(11) NOT NULL,
  PRIMARY KEY("key")
);
CREATE TABLE "cache_locks"(
  "key" varchar(255) NOT NULL,
  "owner" varchar(255) NOT NULL,
  "expiration" int(11) NOT NULL,
  PRIMARY KEY("key")
);
CREATE TABLE "courses"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "acronym" varchar(8) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "courses_acronym_unarchived_unique" ON "courses"(
  "acronym",
  "unarchived"
);
CREATE TABLE "event_attachment_sets"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "caption" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_attachment_sets_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_attachments"(
  "id" integer not null primary key autoincrement,
  "event_attachment_set_id" bigint(20) NOT NULL,
  "image_filepath" varchar(255) NOT NULL,
  "preview_filepath" varchar(255) NOT NULL,
  "orientation" varchar(15) NOT NULL,
  "standalone" tinyint(1) NOT NULL,
  "full_width" tinyint(1) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_attachments_event_attachment_set_id_foreign" FOREIGN KEY("event_attachment_set_id") REFERENCES "event_attachment_sets"("id")
);
CREATE TABLE "event_attendances"(
  "id" integer not null primary key autoincrement,
  "student_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "course_id" bigint(20) DEFAULT NULL,
  "student_year_id" bigint(20) DEFAULT NULL,
  "student_section_id" bigint(20) DEFAULT NULL,
  "event_date_id" bigint(20) NOT NULL,
  CONSTRAINT "event_attendances_course_id_foreign" FOREIGN KEY("course_id") REFERENCES "courses"("id"),
  CONSTRAINT "event_attendances_event_date_id_foreign" FOREIGN KEY("event_date_id") REFERENCES "event_dates"("id"),
  CONSTRAINT "event_attendances_student_section_id_foreign" FOREIGN KEY("student_section_id") REFERENCES "student_sections"("id"),
  CONSTRAINT "event_attendances_student_year_id_foreign" FOREIGN KEY("student_year_id") REFERENCES "student_years"("id"),
  CONSTRAINT "events_attendance_student_id_foreign" FOREIGN KEY("student_id") REFERENCES "students"("id")
);
CREATE TABLE "event_attendees"(
  "id" integer not null primary key autoincrement,
  "event_student_id" bigint(20) NOT NULL,
  "event_date_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "eval_mail_sent" tinyint(1) NOT NULL DEFAULT 0,
  CONSTRAINT "event_attendees_event_date_id_foreign" FOREIGN KEY("event_date_id") REFERENCES "event_dates"("id"),
  CONSTRAINT "event_attendees_event_student_id_foreign" FOREIGN KEY("event_student_id") REFERENCES "event_students"("id")
);
CREATE TABLE "event_dates"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "date" date NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "start_time" time NOT NULL,
  "end_time" time NOT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  CONSTRAINT "event_dates_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE UNIQUE INDEX "event_dates_public_id_unique" ON "event_dates"(
  "public_id"
);
CREATE TABLE "event_deliverable_assignee"(
  "id" integer not null primary key autoincrement,
  "event_deliverable_id" bigint(20) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_deliverable_assignee_event_deliverable_id_foreign" FOREIGN KEY("event_deliverable_id") REFERENCES "event_deliverables"("id"),
  CONSTRAINT "event_deliverable_assignee_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "event_deliverable_tasks"(
  "id" integer not null primary key autoincrement,
  "event_deliverable_id" bigint(20) NOT NULL,
  "name" varchar(255) NOT NULL,
  "is_done" tinyint(1) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_deliverable_tasks_event_deliverable_id_foreign" FOREIGN KEY("event_deliverable_id") REFERENCES "event_deliverables"("id")
);
CREATE TABLE "event_deliverables"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "event_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_deliverables_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_editor"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_editor_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id"),
  CONSTRAINT "event_editor_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "event_eval_forms"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "introduction" text DEFAULT NULL,
  "overall_satisfaction" varchar(255) DEFAULT NULL,
  "content_relevance" varchar(255) DEFAULT NULL,
  "speaker_effectiveness" varchar(255) DEFAULT NULL,
  "engagement_level" varchar(255) DEFAULT NULL,
  "duration" varchar(255) DEFAULT NULL,
  "topics_covered" varchar(255) DEFAULT NULL,
  "suggestions_for_improvement" varchar(255) DEFAULT NULL,
  "future_topics" varchar(255) DEFAULT NULL,
  "overall_experience" varchar(255) DEFAULT NULL,
  "additional_comments" varchar(255) DEFAULT NULL,
  "acknowledgement" text DEFAULT NULL,
  "default" tinyint(1) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_eval_form_questions_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE UNIQUE INDEX "event_eval_form_questions_default_unique" ON "event_eval_forms"(
  "default"
);
CREATE TABLE "event_evaluation_tokens"(
  "token" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY("token")
);
CREATE TABLE "event_evaluations"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "overall_satisfaction" tinyint(4) NOT NULL,
  "content_relevance" tinyint(4) NOT NULL,
  "speaker_effectiveness" tinyint(4) NOT NULL,
  "engagement_level" tinyint(4) NOT NULL,
  "duration" tinyint(4) NOT NULL,
  "topics_covered" text NOT NULL,
  "suggestions_for_improvement" text NOT NULL,
  "future_topics" text NOT NULL,
  "overall_experience" text NOT NULL,
  "additional_comments" text DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "feature_topics_covered" tinyint(1) NOT NULL DEFAULT 0,
  "feature_suggestions_for_improvement" tinyint(1) NOT NULL DEFAULT 0,
  "feature_future_topics" tinyint(1) NOT NULL DEFAULT 0,
  "feature_overall_experience" tinyint(1) NOT NULL DEFAULT 0,
  "feature_additional_comments" tinyint(1) NOT NULL DEFAULT 0,
  CONSTRAINT "event_evaluations_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_links"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "url" varchar(2000) NOT NULL,
  "event_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_links_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_officer_attendees"(
  "id" integer not null primary key autoincrement,
  "event_date_id" bigint(20) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_officer_attendees_event_date_id_foreign" FOREIGN KEY("event_date_id") REFERENCES "event_dates"("id"),
  CONSTRAINT "event_officer_attendees_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "event_participant_courses"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "course_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_participant_courses_course_id_foreign" FOREIGN KEY("course_id") REFERENCES "courses"("id"),
  CONSTRAINT "event_participant_courses_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_participants"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "student_year_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_participants_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id"),
  CONSTRAINT "event_participants_student_year_id_foreign" FOREIGN KEY("student_year_id") REFERENCES "student_years"("id")
);
CREATE TABLE "event_regis_forms"(
  "id" integer not null primary key autoincrement,
  "event_id" bigint(20) NOT NULL,
  "introduction" text DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "event_regis_forms_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "event_registrations"(
  "id" integer not null primary key autoincrement,
  "public_id" bigint(20) DEFAULT NULL,
  "event_id" bigint(20) NOT NULL,
  "token" char(26) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "event_student_id" bigint(20) NOT NULL,
  CONSTRAINT "event_registrations_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id"),
  CONSTRAINT "event_registrations_event_student_id_foreign" FOREIGN KEY("event_student_id") REFERENCES "event_students"("id")
);
CREATE UNIQUE INDEX "event_registrations_public_id_unique" ON "event_registrations"(
  "public_id"
);
CREATE TABLE "event_students"(
  "id" integer not null primary key autoincrement,
  "student_id" varchar(20) NOT NULL,
  "first_name" varchar(50) NOT NULL,
  "middle_name" varchar(50) DEFAULT NULL,
  "last_name" varchar(50) NOT NULL,
  "suffix_name" varchar(50) DEFAULT NULL,
  "course_id" bigint(20) NOT NULL,
  "student_year_id" bigint(20) NOT NULL,
  "email" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "student_section_id" bigint(20) DEFAULT NULL,
  CONSTRAINT "event_students_course_id_foreign" FOREIGN KEY("course_id") REFERENCES "courses"("id"),
  CONSTRAINT "event_students_student_section_id_foreign" FOREIGN KEY("student_section_id") REFERENCES "student_sections"("id"),
  CONSTRAINT "event_students_student_year_id_foreign" FOREIGN KEY("student_year_id") REFERENCES "student_years"("id")
);
CREATE TABLE "events"(
  "id" integer not null primary key autoincrement,
  "description" text DEFAULT NULL,
  "letter_of_intent" varchar(255) DEFAULT NULL,
  "venue" varchar(255) DEFAULT NULL,
  "accomplishment_report" varchar(255) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "narrative" text DEFAULT NULL,
  "gpoa_activity_id" bigint(20) NOT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  "tag" varchar(15) DEFAULT NULL,
  "participant_type" varchar(15) DEFAULT NULL,
  "automatic_attendance" tinyint(1) NOT NULL,
  "accept_evaluation" tinyint(1) NOT NULL,
  "timezone" varchar(50) NOT NULL DEFAULT 'UTC',
  "evaluation_delay_hours" tinyint(3) NOT NULL DEFAULT 24,
  "banner_filepath" varchar(255) DEFAULT NULL,
  CONSTRAINT "events_gpoa_activity_id_foreign" FOREIGN KEY("gpoa_activity_id") REFERENCES "gpoa_activities"("id"),
  CONSTRAINT "chk_participant_type" CHECK("participant_type" in('students','officers'))
);
CREATE UNIQUE INDEX "events_public_id_unique" ON "events"("public_id");
CREATE TABLE "failed_jobs"(
  "id" integer not null primary key autoincrement,
  "uuid" varchar(255) NOT NULL,
  "connection" text NOT NULL,
  "queue" text NOT NULL,
  "payload" longtext NOT NULL,
  "exception" longtext NOT NULL,
  "failed_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" ON "failed_jobs"("uuid");
CREATE TABLE "funds"(
  "id" integer not null primary key autoincrement,
  "collected" decimal(8,2) NOT NULL,
  "spent" decimal(8,2) NOT NULL,
  "remaining" decimal(8,2) NOT NULL,
  "event_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "funds_event_id_foreign" FOREIGN KEY("event_id") REFERENCES "events"("id")
);
CREATE TABLE "gpoa_activities"(
  "id" integer not null primary key autoincrement,
  "gpoa_id" bigint(20) NOT NULL,
  "name" varchar(255) NOT NULL,
  "start_date" date NOT NULL,
  "end_date" date DEFAULT NULL,
  "objectives" text NOT NULL,
  "participants" varchar(100) NOT NULL,
  "proposed_budget" decimal(8,2) DEFAULT NULL,
  "comments" text DEFAULT NULL,
  "adviser_user_id" bigint(20) DEFAULT NULL,
  "president_user_id" bigint(20) DEFAULT NULL,
  "president_approved_at" timestamp NULL DEFAULT NULL,
  "adviser_approved_at" timestamp NULL DEFAULT NULL,
  "rejected_at" timestamp NULL DEFAULT NULL,
  "status" varchar(20) NOT NULL,
  "current_step" varchar(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "number_of_participants" smallint(5) NOT NULL,
  "gpoa_activity_fund_source_id" bigint(20) DEFAULT NULL,
  "gpoa_activity_partnership_type_id" bigint(20) DEFAULT NULL,
  "gpoa_activity_mode_id" bigint(20) NOT NULL,
  "gpoa_activity_type_id" bigint(20) NOT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  "officers_submitted_at" timestamp NULL DEFAULT NULL,
  "president_submitted_at" timestamp NULL DEFAULT NULL,
  "president_returned_at" timestamp NULL DEFAULT NULL,
  "adviser_returned_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "gpoa_activities_adviser_user_id_foreign" FOREIGN KEY("adviser_user_id") REFERENCES "users"("id"),
  CONSTRAINT "gpoa_activities_gpoa_activity_fund_source_id_foreign" FOREIGN KEY("gpoa_activity_fund_source_id") REFERENCES "gpoa_activity_fund_sources"("id"),
  CONSTRAINT "gpoa_activities_gpoa_activity_mode_id_foreign" FOREIGN KEY("gpoa_activity_mode_id") REFERENCES "gpoa_activity_modes"("id"),
  CONSTRAINT "gpoa_activities_gpoa_activity_partnership_type_id_foreign" FOREIGN KEY("gpoa_activity_partnership_type_id") REFERENCES "gpoa_activity_partnership_types"("id"),
  CONSTRAINT "gpoa_activities_gpoa_activity_type_id_foreign" FOREIGN KEY("gpoa_activity_type_id") REFERENCES "gpoa_activity_types"("id"),
  CONSTRAINT "gpoa_activities_gpoa_id_foreign" FOREIGN KEY("gpoa_id") REFERENCES "gpoas"("id"),
  CONSTRAINT "gpoa_activities_president_user_id_foreign" FOREIGN KEY("president_user_id") REFERENCES "users"("id"),
  CONSTRAINT "chk_status" CHECK("status" in('draft','pending','returned','approved','rejected')),
  CONSTRAINT "chk_current_step" CHECK("current_step" in('officers','president','adviser'))
);
CREATE UNIQUE INDEX "gpoa_activities_public_id_unique" ON "gpoa_activities"(
  "public_id"
);
CREATE TABLE "gpoa_activity_authors"(
  "id" integer not null primary key autoincrement,
  "gpoa_activity_id" bigint(20) NOT NULL,
  "officer_user_id" bigint(20) NOT NULL,
  "role" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "gpoa_activity_authors_gpoa_activity_id_foreign" FOREIGN KEY("gpoa_activity_id") REFERENCES "gpoa_activities"("id"),
  CONSTRAINT "gpoa_activity_authors_officer_user_id_foreign" FOREIGN KEY("officer_user_id") REFERENCES "users"("id"),
  CONSTRAINT "chk_role" CHECK("role" in('event head','co-head'))
);
CREATE TABLE "gpoa_activity_event_heads"(
  "id" integer not null primary key autoincrement,
  "gpoa_activity_id" bigint(20) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "role" varchar(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "gpoa_activity_event_heads_gpoa_activity_id_foreign" FOREIGN KEY("gpoa_activity_id") REFERENCES "gpoa_activities"("id"),
  CONSTRAINT "gpoa_activity_event_heads_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id"),
  CONSTRAINT "chk_role" CHECK("role" in('event head','co-head'))
);
CREATE TABLE "gpoa_activity_fund_sources"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "gpoa_activity_fund_sources_name_unarchived_unique" ON "gpoa_activity_fund_sources"(
  "name",
  "unarchived"
);
CREATE TABLE "gpoa_activity_modes"(
  "id" integer not null primary key autoincrement,
  "name" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "gpoa_activity_modes_name_unarchived_unique" ON "gpoa_activity_modes"(
  "name",
  "unarchived"
);
CREATE TABLE "gpoa_activity_participants"(
  "id" integer not null primary key autoincrement,
  "student_year_id" bigint(20) NOT NULL,
  "gpoa_activity_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "gpoa_activity_participants_gpoa_activity_id_foreign" FOREIGN KEY("gpoa_activity_id") REFERENCES "gpoa_activities"("id"),
  CONSTRAINT "gpoa_activity_participants_student_year_id_foreign" FOREIGN KEY("student_year_id") REFERENCES "student_years"("id")
);
CREATE TABLE "gpoa_activity_partnership_types"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "gpoa_activity_partnership_types_name_unarchived_unique" ON "gpoa_activity_partnership_types"(
  "name",
  "unarchived"
);
CREATE TABLE "gpoa_activity_types"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "gpoa_activity_types_name_unarchived_unique" ON "gpoa_activity_types"(
  "name",
  "unarchived"
);
CREATE TABLE "gpoas"(
  "id" integer not null primary key autoincrement,
  "academic_period_id" bigint(20) NOT NULL,
  "creator_user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  "report_filepath" varchar(255) DEFAULT NULL,
  "accom_report_filepath" varchar(255) DEFAULT NULL,
  "closed_at" timestamp NULL DEFAULT NULL,
  "closer_user_id" bigint(20) DEFAULT NULL,
  "active" tinyint(4) GENERATED ALWAYS AS(if("closed_at" is null,1,NULL)) STORED,
  "report_file_updated" tinyint(1) DEFAULT NULL,
  "report_file_updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "gpoas_academic_period_id_foreign" FOREIGN KEY("academic_period_id") REFERENCES "academic_periods"("id"),
  CONSTRAINT "gpoas_closer_user_id_foreign" FOREIGN KEY("closer_user_id") REFERENCES "users"("id"),
  CONSTRAINT "gpoas_creator_user_id_foreign" FOREIGN KEY("creator_user_id") REFERENCES "users"("id")
);
CREATE UNIQUE INDEX "gpoas_active_unique" ON "gpoas"("active");
CREATE TABLE "job_batches"(
  "id" varchar(255) NOT NULL,
  "name" varchar(255) NOT NULL,
  "total_jobs" int(11) NOT NULL,
  "pending_jobs" int(11) NOT NULL,
  "failed_jobs" int(11) NOT NULL,
  "failed_job_ids" longtext NOT NULL,
  "options" mediumtext DEFAULT NULL,
  "cancelled_at" int(11) DEFAULT NULL,
  "created_at" int(11) NOT NULL,
  "finished_at" int(11) DEFAULT NULL,
  PRIMARY KEY("id")
);
CREATE TABLE "jobs"(
  "id" integer not null primary key autoincrement,
  "queue" varchar(255) NOT NULL,
  "payload" longtext NOT NULL,
  "attempts" tinyint(3) NOT NULL,
  "reserved_at" int(10) DEFAULT NULL,
  "available_at" int(10) NOT NULL,
  "created_at" int(10) NOT NULL
);
CREATE TABLE "meetings"(
  "id" integer not null primary key autoincrement,
  "title" varchar(255) NOT NULL,
  "date" date NOT NULL,
  "venue" varchar(255) NOT NULL,
  "agenda" varchar(255) NOT NULL,
  "participants" int(11) NOT NULL,
  "minutes_file" varchar(255) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "meetings_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "migrations"(
  "id" integer not null primary key autoincrement,
  "migration" varchar(255) NOT NULL,
  "batch" int(11) NOT NULL
);
CREATE TABLE "partnerships"(
  "id" integer not null primary key autoincrement,
  "organization_name" varchar(255) NOT NULL,
  "purpose" varchar(255) NOT NULL,
  "benefits" varchar(255) NOT NULL,
  "action" varchar(255) NOT NULL,
  "links" varchar(255) NOT NULL,
  "accomplished_by" varchar(255) NOT NULL,
  "officer" varchar(255) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "partnerships_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "password_reset_tokens"(
  "email" varchar(255) NOT NULL,
  "token" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY("email")
);
CREATE TABLE "permissions"(
  "id" integer not null primary key autoincrement,
  "resource_type_id" bigint(20) NOT NULL,
  "resource_action_type_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "permissions_resource_action_type_id_foreign" FOREIGN KEY("resource_action_type_id") REFERENCES "resource_action_types"("id"),
  CONSTRAINT "permissions_resource_type_id_foreign" FOREIGN KEY("resource_type_id") REFERENCES "resource_types"("id")
);
CREATE TABLE "personal_access_tokens"(
  "id" integer not null primary key autoincrement,
  "tokenable_type" varchar(255) NOT NULL,
  "tokenable_id" bigint(20) NOT NULL,
  "name" varchar(255) NOT NULL,
  "token" varchar(64) NOT NULL,
  "abilities" text DEFAULT NULL,
  "last_used_at" timestamp NULL DEFAULT NULL,
  "expires_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" ON "personal_access_tokens"(
  "token"
);
CREATE TABLE "platforms"(
  "id" integer not null primary key autoincrement,
  "name" varchar(255) NOT NULL,
  "description" varchar(255) NOT NULL,
  "start_date" date NOT NULL,
  "end_date" date NOT NULL,
  "progress" decimal(5,2) NOT NULL,
  "user_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "platforms_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE TABLE "position_categories"(
  "id" integer not null primary key autoincrement,
  "name" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);
CREATE TABLE "position_category"(
  "id" integer not null primary key autoincrement,
  "position_id" bigint(20) NOT NULL,
  "position_category_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "position_category_position_category_id_foreign" FOREIGN KEY("position_category_id") REFERENCES "position_categories"("id"),
  CONSTRAINT "position_category_position_id_foreign" FOREIGN KEY("position_id") REFERENCES "positions"("id")
);
CREATE TABLE "position_permissions"(
  "id" integer not null primary key autoincrement,
  "position_id" bigint(20) NOT NULL,
  "permission_id" bigint(20) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "position_permissions_permission_id_foreign" FOREIGN KEY("permission_id") REFERENCES "permissions"("id"),
  CONSTRAINT "position_permissions_position_id_foreign" FOREIGN KEY("position_id") REFERENCES "positions"("id")
);
CREATE TABLE "positions"(
  "id" integer not null primary key autoincrement,
  "name" varchar(100) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "position_order" tinyint(3) NOT NULL
);
CREATE UNIQUE INDEX "positions_name_unique" ON "positions"("name");
CREATE TABLE "resource_action_types"(
  "id" integer not null primary key autoincrement,
  "name" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);
CREATE UNIQUE INDEX "resource_action_types_name_unique" ON "resource_action_types"(
  "name"
);
CREATE TABLE "resource_types"(
  "id" integer not null primary key autoincrement,
  "name" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);
CREATE UNIQUE INDEX "resource_types_name_unique" ON "resource_types"("name");
CREATE TABLE "roles"(
  "id" integer not null primary key autoincrement,
  "name" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);
CREATE TABLE "sessions"(
  "id" varchar(255) NOT NULL,
  "user_id" bigint(20) DEFAULT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "user_agent" text DEFAULT NULL,
  "payload" longtext NOT NULL,
  "last_activity" int(11) NOT NULL,
  PRIMARY KEY("id")
);
CREATE TABLE "signup_invitations"(
  "id" integer not null primary key autoincrement,
  "invite_code" varchar(255) NOT NULL,
  "email" varchar(255) NOT NULL,
  "position_id" bigint(20) DEFAULT NULL,
  "is_accepted" tinyint(1) NOT NULL,
  "expires_at" datetime NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "email_sent" tinyint(1) DEFAULT NULL,
  CONSTRAINT "signup_invitations_position_id_foreign" FOREIGN KEY("position_id") REFERENCES "positions"("id")
);
CREATE TABLE "student_sections"(
  "id" integer not null primary key autoincrement,
  "section" varchar(10) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "student_sections_section_unarchived_unique" ON "student_sections"(
  "section",
  "unarchived"
);
CREATE TABLE "student_years"(
  "id" integer not null primary key autoincrement,
  "year" varchar(4) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "label" varchar(15) NOT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED
);
CREATE UNIQUE INDEX "student_years_label_unarchived_unique" ON "student_years"(
  "label",
  "unarchived"
);
CREATE TABLE "students"(
  "id" integer not null primary key autoincrement,
  "student_id" varchar(20) NOT NULL,
  "first_name" varchar(50) NOT NULL,
  "middle_name" varchar(50) DEFAULT NULL,
  "last_name" varchar(50) NOT NULL,
  "suffix_name" varchar(10) DEFAULT NULL,
  "course_id" bigint(20) NOT NULL,
  "student_year_id" bigint(20) NOT NULL,
  "student_section_id" bigint(20) NOT NULL,
  "email" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  CONSTRAINT "students_course_id_foreign" FOREIGN KEY("course_id") REFERENCES "courses"("id"),
  CONSTRAINT "students_student_section_id_foreign" FOREIGN KEY("student_section_id") REFERENCES "student_sections"("id"),
  CONSTRAINT "students_student_year_id_foreign" FOREIGN KEY("student_year_id") REFERENCES "student_years"("id")
);
CREATE UNIQUE INDEX "students_public_id_unique" ON "students"("public_id");
CREATE TABLE "user_google_accounts"(
  "id" integer not null primary key autoincrement,
  "user_id" bigint(20) NOT NULL,
  "google_id" text NOT NULL,
  "token" text NOT NULL,
  "refresh_token" text DEFAULT NULL,
  "expires_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "user_google_accounts_user_id_foreign" FOREIGN KEY("user_id") REFERENCES "users"("id")
);
CREATE UNIQUE INDEX "user_google_accounts_google_id_unique" ON "user_google_accounts"(
  "google_id"
);
CREATE TABLE "users"(
  "id" integer not null primary key autoincrement,
  "remember_token" varchar(100) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "first_name" varchar(50) DEFAULT NULL,
  "middle_name" varchar(50) DEFAULT NULL,
  "last_name" varchar(50) DEFAULT NULL,
  "suffix_name" varchar(10) DEFAULT NULL,
  "email" varchar(255) DEFAULT NULL,
  "role_id" bigint(20) DEFAULT NULL,
  "position_id" bigint(20) DEFAULT NULL,
  "username" varchar(50) DEFAULT NULL,
  "password" varchar(255) DEFAULT NULL,
  "avatar_filepath" varchar(255) DEFAULT NULL,
  "google_id" text DEFAULT NULL,
  "google_token" text DEFAULT NULL,
  "google_refresh_token" text DEFAULT NULL,
  "google_expires_at" datetime DEFAULT NULL,
  "public_id" bigint(20) DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "unarchived" tinyint(4) GENERATED ALWAYS AS(if("deleted_at" is null,1,NULL)) STORED,
  "email_verified_at" timestamp NULL DEFAULT NULL,
  CONSTRAINT "users_position_id_foreign" FOREIGN KEY("position_id") REFERENCES "positions"("id"),
  CONSTRAINT "users_role_id_foreign" FOREIGN KEY("role_id") REFERENCES "roles"("id")
);
CREATE UNIQUE INDEX "users_google_id_unarchived_unique" ON "users"(
  "google_id",
  "unarchived"
);
CREATE TRIGGER "audit_academic_periods_insert"
after INSERT on "academic_periods"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'academic_periods',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_academic_periods_update"
after UPDATE on "academic_periods"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'start_date')
where old."start_date" <> new."start_date" 
or (old."start_date" is not null and new."start_date" is null)
or (old."start_date" is null and new."start_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'end_date')
where old."end_date" <> new."end_date" 
or (old."end_date" is not null and new."end_date" is null)
or (old."end_date" is null and new."end_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'academic_term_id')
where old."academic_term_id" <> new."academic_term_id" 
or (old."academic_term_id" is not null and new."academic_term_id" is null)
or (old."academic_term_id" is null and new."academic_term_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'year_label')
where old."year_label" <> new."year_label" 
or (old."year_label" is not null and new."year_label" is null)
or (old."year_label" is null and new."year_label" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'head_of_student_services')
where old."head_of_student_services" <> new."head_of_student_services" 
or (old."head_of_student_services" is not null and new."head_of_student_services" is null)
or (old."head_of_student_services" is null and new."head_of_student_services" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'branch_director')
where old."branch_director" <> new."branch_director" 
or (old."branch_director" is not null and new."branch_director" is null)
or (old."branch_director" is null and new."branch_director" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'academic_periods',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_academic_periods_delete"
after DELETE on "academic_periods"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'academic_periods',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_academic_terms_insert"
after INSERT on "academic_terms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'academic_terms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_academic_terms_update"
after UPDATE on "academic_terms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'system')
where old."system" <> new."system" 
or (old."system" is not null and new."system" is null)
or (old."system" is null and new."system" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'term_number')
where old."term_number" <> new."term_number" 
or (old."term_number" is not null and new."term_number" is null)
or (old."term_number" is null and new."term_number" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'label')
where old."label" <> new."label" 
or (old."label" is not null and new."label" is null)
or (old."label" is null and new."label" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'academic_terms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_academic_terms_delete"
after DELETE on "academic_terms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'academic_terms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_accom_reports_insert"
after INSERT on "accom_reports"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'accom_reports',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_accom_reports_update"
after UPDATE on "accom_reports"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'approved_ar_filepath')
where old."approved_ar_filepath" <> new."approved_ar_filepath" 
or (old."approved_ar_filepath" is not null and new."approved_ar_filepath" is null)
or (old."approved_ar_filepath" is null and new."approved_ar_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'current_step')
where old."current_step" <> new."current_step" 
or (old."current_step" is not null and new."current_step" is null)
or (old."current_step" is null and new."current_step" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'status')
where old."status" <> new."status" 
or (old."status" is not null and new."status" is null)
or (old."status" is null and new."status" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'comments')
where old."comments" <> new."comments" 
or (old."comments" is not null and new."comments" is null)
or (old."comments" is null and new."comments" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'approved_at')
where old."approved_at" <> new."approved_at" 
or (old."approved_at" is not null and new."approved_at" is null)
or (old."approved_at" is null and new."approved_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'returned_at')
where old."returned_at" <> new."returned_at" 
or (old."returned_at" is not null and new."returned_at" is null)
or (old."returned_at" is null and new."returned_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'submitted_at')
where old."submitted_at" <> new."submitted_at" 
or (old."submitted_at" is not null and new."submitted_at" is null)
or (old."submitted_at" is null and new."submitted_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'president_user_id')
where old."president_user_id" <> new."president_user_id" 
or (old."president_user_id" is not null and new."president_user_id" is null)
or (old."president_user_id" is null and new."president_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'filepath')
where old."filepath" <> new."filepath" 
or (old."filepath" is not null and new."filepath" is null)
or (old."filepath" is null and new."filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'file_updated')
where old."file_updated" <> new."file_updated" 
or (old."file_updated" is not null and new."file_updated" is null)
or (old."file_updated" is null and new."file_updated" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'file_updated_at')
where old."file_updated_at" <> new."file_updated_at" 
or (old."file_updated_at" is not null and new."file_updated_at" is null)
or (old."file_updated_at" is null and new."file_updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'accom_reports',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_accom_reports_delete"
after DELETE on "accom_reports"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'accom_reports',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_activity_logs_insert"
after INSERT on "activity_logs"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'activity_logs',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_activity_logs_update"
after UPDATE on "activity_logs"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'category')
where old."category" <> new."category" 
or (old."category" is not null and new."category" is null)
or (old."category" is null and new."category" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'action')
where old."action" <> new."action" 
or (old."action" is not null and new."action" is null)
or (old."action" is null and new."action" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'item_id')
where old."item_id" <> new."item_id" 
or (old."item_id" is not null and new."item_id" is null)
or (old."item_id" is null and new."item_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'date')
where old."date" <> new."date" 
or (old."date" is not null and new."date" is null)
or (old."date" is null and new."date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'activity_logs',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_activity_logs_delete"
after DELETE on "activity_logs"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'activity_logs',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_announcements_insert"
after INSERT on "announcements"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'announcements',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_announcements_update"
after UPDATE on "announcements"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'title')
where old."title" <> new."title" 
or (old."title" is not null and new."title" is null)
or (old."title" is null and new."title" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'introduction')
where old."introduction" <> new."introduction" 
or (old."introduction" is not null and new."introduction" is null)
or (old."introduction" is null and new."introduction" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'message')
where old."message" <> new."message" 
or (old."message" is not null and new."message" is null)
or (old."message" is null and new."message" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'announcements',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_announcements_delete"
after DELETE on "announcements"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'announcements',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_courses_insert"
after INSERT on "courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_courses_update"
after UPDATE on "courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'acronym')
where old."acronym" <> new."acronym" 
or (old."acronym" is not null and new."acronym" is null)
or (old."acronym" is null and new."acronym" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_courses_delete"
after DELETE on "courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachment_sets_insert"
after INSERT on "event_attachment_sets"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_attachment_sets',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachment_sets_update"
after UPDATE on "event_attachment_sets"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'caption')
where old."caption" <> new."caption" 
or (old."caption" is not null and new."caption" is null)
or (old."caption" is null and new."caption" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_attachment_sets',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachment_sets_delete"
after DELETE on "event_attachment_sets"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_attachment_sets',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachments_insert"
after INSERT on "event_attachments"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_attachments',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachments_update"
after UPDATE on "event_attachments"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_attachment_set_id')
where old."event_attachment_set_id" <> new."event_attachment_set_id" 
or (old."event_attachment_set_id" is not null and new."event_attachment_set_id" is null)
or (old."event_attachment_set_id" is null and new."event_attachment_set_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'image_filepath')
where old."image_filepath" <> new."image_filepath" 
or (old."image_filepath" is not null and new."image_filepath" is null)
or (old."image_filepath" is null and new."image_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'preview_filepath')
where old."preview_filepath" <> new."preview_filepath" 
or (old."preview_filepath" is not null and new."preview_filepath" is null)
or (old."preview_filepath" is null and new."preview_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'orientation')
where old."orientation" <> new."orientation" 
or (old."orientation" is not null and new."orientation" is null)
or (old."orientation" is null and new."orientation" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'standalone')
where old."standalone" <> new."standalone" 
or (old."standalone" is not null and new."standalone" is null)
or (old."standalone" is null and new."standalone" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'full_width')
where old."full_width" <> new."full_width" 
or (old."full_width" is not null and new."full_width" is null)
or (old."full_width" is null and new."full_width" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_attachments',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attachments_delete"
after DELETE on "event_attachments"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_attachments',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendances_insert"
after INSERT on "event_attendances"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_attendances',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendances_update"
after UPDATE on "event_attendances"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_id')
where old."student_id" <> new."student_id" 
or (old."student_id" is not null and new."student_id" is null)
or (old."student_id" is null and new."student_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'course_id')
where old."course_id" <> new."course_id" 
or (old."course_id" is not null and new."course_id" is null)
or (old."course_id" is null and new."course_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_year_id')
where old."student_year_id" <> new."student_year_id" 
or (old."student_year_id" is not null and new."student_year_id" is null)
or (old."student_year_id" is null and new."student_year_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_section_id')
where old."student_section_id" <> new."student_section_id" 
or (old."student_section_id" is not null and new."student_section_id" is null)
or (old."student_section_id" is null and new."student_section_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_date_id')
where old."event_date_id" <> new."event_date_id" 
or (old."event_date_id" is not null and new."event_date_id" is null)
or (old."event_date_id" is null and new."event_date_id" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_attendances',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendances_delete"
after DELETE on "event_attendances"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_attendances',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendees_insert"
after INSERT on "event_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendees_update"
after UPDATE on "event_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_student_id')
where old."event_student_id" <> new."event_student_id" 
or (old."event_student_id" is not null and new."event_student_id" is null)
or (old."event_student_id" is null and new."event_student_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_date_id')
where old."event_date_id" <> new."event_date_id" 
or (old."event_date_id" is not null and new."event_date_id" is null)
or (old."event_date_id" is null and new."event_date_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'eval_mail_sent')
where old."eval_mail_sent" <> new."eval_mail_sent" 
or (old."eval_mail_sent" is not null and new."eval_mail_sent" is null)
or (old."eval_mail_sent" is null and new."eval_mail_sent" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_attendees_delete"
after DELETE on "event_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_dates_insert"
after INSERT on "event_dates"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_dates',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_dates_update"
after UPDATE on "event_dates"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'date')
where old."date" <> new."date" 
or (old."date" is not null and new."date" is null)
or (old."date" is null and new."date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'start_time')
where old."start_time" <> new."start_time" 
or (old."start_time" is not null and new."start_time" is null)
or (old."start_time" is null and new."start_time" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'end_time')
where old."end_time" <> new."end_time" 
or (old."end_time" is not null and new."end_time" is null)
or (old."end_time" is null and new."end_time" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_dates',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_dates_delete"
after DELETE on "event_dates"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_dates',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_assignee_insert"
after INSERT on "event_deliverable_assignee"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_deliverable_assignee',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_assignee_update"
after UPDATE on "event_deliverable_assignee"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_deliverable_id')
where old."event_deliverable_id" <> new."event_deliverable_id" 
or (old."event_deliverable_id" is not null and new."event_deliverable_id" is null)
or (old."event_deliverable_id" is null and new."event_deliverable_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_deliverable_assignee',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_assignee_delete"
after DELETE on "event_deliverable_assignee"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_deliverable_assignee',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_tasks_insert"
after INSERT on "event_deliverable_tasks"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_deliverable_tasks',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_tasks_update"
after UPDATE on "event_deliverable_tasks"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_deliverable_id')
where old."event_deliverable_id" <> new."event_deliverable_id" 
or (old."event_deliverable_id" is not null and new."event_deliverable_id" is null)
or (old."event_deliverable_id" is null and new."event_deliverable_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'is_done')
where old."is_done" <> new."is_done" 
or (old."is_done" is not null and new."is_done" is null)
or (old."is_done" is null and new."is_done" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_deliverable_tasks',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverable_tasks_delete"
after DELETE on "event_deliverable_tasks"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_deliverable_tasks',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverables_insert"
after INSERT on "event_deliverables"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_deliverables',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverables_update"
after UPDATE on "event_deliverables"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_deliverables',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_deliverables_delete"
after DELETE on "event_deliverables"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_deliverables',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_editor_insert"
after INSERT on "event_editor"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_editor',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_editor_update"
after UPDATE on "event_editor"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_editor',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_editor_delete"
after DELETE on "event_editor"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_editor',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_eval_forms_insert"
after INSERT on "event_eval_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_eval_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_eval_forms_update"
after UPDATE on "event_eval_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'introduction')
where old."introduction" <> new."introduction" 
or (old."introduction" is not null and new."introduction" is null)
or (old."introduction" is null and new."introduction" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'overall_satisfaction')
where old."overall_satisfaction" <> new."overall_satisfaction" 
or (old."overall_satisfaction" is not null and new."overall_satisfaction" is null)
or (old."overall_satisfaction" is null and new."overall_satisfaction" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'content_relevance')
where old."content_relevance" <> new."content_relevance" 
or (old."content_relevance" is not null and new."content_relevance" is null)
or (old."content_relevance" is null and new."content_relevance" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'speaker_effectiveness')
where old."speaker_effectiveness" <> new."speaker_effectiveness" 
or (old."speaker_effectiveness" is not null and new."speaker_effectiveness" is null)
or (old."speaker_effectiveness" is null and new."speaker_effectiveness" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'engagement_level')
where old."engagement_level" <> new."engagement_level" 
or (old."engagement_level" is not null and new."engagement_level" is null)
or (old."engagement_level" is null and new."engagement_level" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'duration')
where old."duration" <> new."duration" 
or (old."duration" is not null and new."duration" is null)
or (old."duration" is null and new."duration" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'topics_covered')
where old."topics_covered" <> new."topics_covered" 
or (old."topics_covered" is not null and new."topics_covered" is null)
or (old."topics_covered" is null and new."topics_covered" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'suggestions_for_improvement')
where old."suggestions_for_improvement" <> new."suggestions_for_improvement" 
or (old."suggestions_for_improvement" is not null and new."suggestions_for_improvement" is null)
or (old."suggestions_for_improvement" is null and new."suggestions_for_improvement" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'future_topics')
where old."future_topics" <> new."future_topics" 
or (old."future_topics" is not null and new."future_topics" is null)
or (old."future_topics" is null and new."future_topics" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'overall_experience')
where old."overall_experience" <> new."overall_experience" 
or (old."overall_experience" is not null and new."overall_experience" is null)
or (old."overall_experience" is null and new."overall_experience" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'additional_comments')
where old."additional_comments" <> new."additional_comments" 
or (old."additional_comments" is not null and new."additional_comments" is null)
or (old."additional_comments" is null and new."additional_comments" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'acknowledgement')
where old."acknowledgement" <> new."acknowledgement" 
or (old."acknowledgement" is not null and new."acknowledgement" is null)
or (old."acknowledgement" is null and new."acknowledgement" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'default')
where old."default" <> new."default" 
or (old."default" is not null and new."default" is null)
or (old."default" is null and new."default" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_eval_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_eval_forms_delete"
after DELETE on "event_eval_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_eval_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_evaluations_insert"
after INSERT on "event_evaluations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_evaluations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_evaluations_update"
after UPDATE on "event_evaluations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'overall_satisfaction')
where old."overall_satisfaction" <> new."overall_satisfaction" 
or (old."overall_satisfaction" is not null and new."overall_satisfaction" is null)
or (old."overall_satisfaction" is null and new."overall_satisfaction" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'content_relevance')
where old."content_relevance" <> new."content_relevance" 
or (old."content_relevance" is not null and new."content_relevance" is null)
or (old."content_relevance" is null and new."content_relevance" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'speaker_effectiveness')
where old."speaker_effectiveness" <> new."speaker_effectiveness" 
or (old."speaker_effectiveness" is not null and new."speaker_effectiveness" is null)
or (old."speaker_effectiveness" is null and new."speaker_effectiveness" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'engagement_level')
where old."engagement_level" <> new."engagement_level" 
or (old."engagement_level" is not null and new."engagement_level" is null)
or (old."engagement_level" is null and new."engagement_level" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'duration')
where old."duration" <> new."duration" 
or (old."duration" is not null and new."duration" is null)
or (old."duration" is null and new."duration" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'topics_covered')
where old."topics_covered" <> new."topics_covered" 
or (old."topics_covered" is not null and new."topics_covered" is null)
or (old."topics_covered" is null and new."topics_covered" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'suggestions_for_improvement')
where old."suggestions_for_improvement" <> new."suggestions_for_improvement" 
or (old."suggestions_for_improvement" is not null and new."suggestions_for_improvement" is null)
or (old."suggestions_for_improvement" is null and new."suggestions_for_improvement" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'future_topics')
where old."future_topics" <> new."future_topics" 
or (old."future_topics" is not null and new."future_topics" is null)
or (old."future_topics" is null and new."future_topics" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'overall_experience')
where old."overall_experience" <> new."overall_experience" 
or (old."overall_experience" is not null and new."overall_experience" is null)
or (old."overall_experience" is null and new."overall_experience" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'additional_comments')
where old."additional_comments" <> new."additional_comments" 
or (old."additional_comments" is not null and new."additional_comments" is null)
or (old."additional_comments" is null and new."additional_comments" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'feature_topics_covered')
where old."feature_topics_covered" <> new."feature_topics_covered" 
or (old."feature_topics_covered" is not null and new."feature_topics_covered" is null)
or (old."feature_topics_covered" is null and new."feature_topics_covered" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'feature_suggestions_for_improvement')
where old."feature_suggestions_for_improvement" <> new."feature_suggestions_for_improvement" 
or (old."feature_suggestions_for_improvement" is not null and new."feature_suggestions_for_improvement" is null)
or (old."feature_suggestions_for_improvement" is null and new."feature_suggestions_for_improvement" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'feature_future_topics')
where old."feature_future_topics" <> new."feature_future_topics" 
or (old."feature_future_topics" is not null and new."feature_future_topics" is null)
or (old."feature_future_topics" is null and new."feature_future_topics" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'feature_overall_experience')
where old."feature_overall_experience" <> new."feature_overall_experience" 
or (old."feature_overall_experience" is not null and new."feature_overall_experience" is null)
or (old."feature_overall_experience" is null and new."feature_overall_experience" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'feature_additional_comments')
where old."feature_additional_comments" <> new."feature_additional_comments" 
or (old."feature_additional_comments" is not null and new."feature_additional_comments" is null)
or (old."feature_additional_comments" is null and new."feature_additional_comments" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_evaluations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_evaluations_delete"
after DELETE on "event_evaluations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_evaluations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_links_insert"
after INSERT on "event_links"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_links',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_links_update"
after UPDATE on "event_links"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'url')
where old."url" <> new."url" 
or (old."url" is not null and new."url" is null)
or (old."url" is null and new."url" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_links',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_links_delete"
after DELETE on "event_links"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_links',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_officer_attendees_insert"
after INSERT on "event_officer_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_officer_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_officer_attendees_update"
after UPDATE on "event_officer_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_date_id')
where old."event_date_id" <> new."event_date_id" 
or (old."event_date_id" is not null and new."event_date_id" is null)
or (old."event_date_id" is null and new."event_date_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_officer_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_officer_attendees_delete"
after DELETE on "event_officer_attendees"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_officer_attendees',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participant_courses_insert"
after INSERT on "event_participant_courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_participant_courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participant_courses_update"
after UPDATE on "event_participant_courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'course_id')
where old."course_id" <> new."course_id" 
or (old."course_id" is not null and new."course_id" is null)
or (old."course_id" is null and new."course_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_participant_courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participant_courses_delete"
after DELETE on "event_participant_courses"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_participant_courses',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participants_insert"
after INSERT on "event_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participants_update"
after UPDATE on "event_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_year_id')
where old."student_year_id" <> new."student_year_id" 
or (old."student_year_id" is not null and new."student_year_id" is null)
or (old."student_year_id" is null and new."student_year_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_participants_delete"
after DELETE on "event_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_regis_forms_insert"
after INSERT on "event_regis_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_regis_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_regis_forms_update"
after UPDATE on "event_regis_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'introduction')
where old."introduction" <> new."introduction" 
or (old."introduction" is not null and new."introduction" is null)
or (old."introduction" is null and new."introduction" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_regis_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_regis_forms_delete"
after DELETE on "event_regis_forms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_regis_forms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_registrations_insert"
after INSERT on "event_registrations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_registrations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_registrations_update"
after UPDATE on "event_registrations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'token')
where old."token" <> new."token" 
or (old."token" is not null and new."token" is null)
or (old."token" is null and new."token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_student_id')
where old."event_student_id" <> new."event_student_id" 
or (old."event_student_id" is not null and new."event_student_id" is null)
or (old."event_student_id" is null and new."event_student_id" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_registrations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_registrations_delete"
after DELETE on "event_registrations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_registrations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_students_insert"
after INSERT on "event_students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'event_students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_students_update"
after UPDATE on "event_students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_id')
where old."student_id" <> new."student_id" 
or (old."student_id" is not null and new."student_id" is null)
or (old."student_id" is null and new."student_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'first_name')
where old."first_name" <> new."first_name" 
or (old."first_name" is not null and new."first_name" is null)
or (old."first_name" is null and new."first_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'middle_name')
where old."middle_name" <> new."middle_name" 
or (old."middle_name" is not null and new."middle_name" is null)
or (old."middle_name" is null and new."middle_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'last_name')
where old."last_name" <> new."last_name" 
or (old."last_name" is not null and new."last_name" is null)
or (old."last_name" is null and new."last_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'suffix_name')
where old."suffix_name" <> new."suffix_name" 
or (old."suffix_name" is not null and new."suffix_name" is null)
or (old."suffix_name" is null and new."suffix_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'course_id')
where old."course_id" <> new."course_id" 
or (old."course_id" is not null and new."course_id" is null)
or (old."course_id" is null and new."course_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_year_id')
where old."student_year_id" <> new."student_year_id" 
or (old."student_year_id" is not null and new."student_year_id" is null)
or (old."student_year_id" is null and new."student_year_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email')
where old."email" <> new."email" 
or (old."email" is not null and new."email" is null)
or (old."email" is null and new."email" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_section_id')
where old."student_section_id" <> new."student_section_id" 
or (old."student_section_id" is not null and new."student_section_id" is null)
or (old."student_section_id" is null and new."student_section_id" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'event_students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_event_students_delete"
after DELETE on "event_students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'event_students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_events_insert"
after INSERT on "events"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'events',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_events_update"
after UPDATE on "events"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'description')
where old."description" <> new."description" 
or (old."description" is not null and new."description" is null)
or (old."description" is null and new."description" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'letter_of_intent')
where old."letter_of_intent" <> new."letter_of_intent" 
or (old."letter_of_intent" is not null and new."letter_of_intent" is null)
or (old."letter_of_intent" is null and new."letter_of_intent" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'venue')
where old."venue" <> new."venue" 
or (old."venue" is not null and new."venue" is null)
or (old."venue" is null and new."venue" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'accomplishment_report')
where old."accomplishment_report" <> new."accomplishment_report" 
or (old."accomplishment_report" is not null and new."accomplishment_report" is null)
or (old."accomplishment_report" is null and new."accomplishment_report" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'narrative')
where old."narrative" <> new."narrative" 
or (old."narrative" is not null and new."narrative" is null)
or (old."narrative" is null and new."narrative" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_id')
where old."gpoa_activity_id" <> new."gpoa_activity_id" 
or (old."gpoa_activity_id" is not null and new."gpoa_activity_id" is null)
or (old."gpoa_activity_id" is null and new."gpoa_activity_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'tag')
where old."tag" <> new."tag" 
or (old."tag" is not null and new."tag" is null)
or (old."tag" is null and new."tag" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'participant_type')
where old."participant_type" <> new."participant_type" 
or (old."participant_type" is not null and new."participant_type" is null)
or (old."participant_type" is null and new."participant_type" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'automatic_attendance')
where old."automatic_attendance" <> new."automatic_attendance" 
or (old."automatic_attendance" is not null and new."automatic_attendance" is null)
or (old."automatic_attendance" is null and new."automatic_attendance" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'accept_evaluation')
where old."accept_evaluation" <> new."accept_evaluation" 
or (old."accept_evaluation" is not null and new."accept_evaluation" is null)
or (old."accept_evaluation" is null and new."accept_evaluation" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'timezone')
where old."timezone" <> new."timezone" 
or (old."timezone" is not null and new."timezone" is null)
or (old."timezone" is null and new."timezone" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'evaluation_delay_hours')
where old."evaluation_delay_hours" <> new."evaluation_delay_hours" 
or (old."evaluation_delay_hours" is not null and new."evaluation_delay_hours" is null)
or (old."evaluation_delay_hours" is null and new."evaluation_delay_hours" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'banner_filepath')
where old."banner_filepath" <> new."banner_filepath" 
or (old."banner_filepath" is not null and new."banner_filepath" is null)
or (old."banner_filepath" is null and new."banner_filepath" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'events',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_events_delete"
after DELETE on "events"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'events',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_funds_insert"
after INSERT on "funds"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'funds',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_funds_update"
after UPDATE on "funds"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'collected')
where old."collected" <> new."collected" 
or (old."collected" is not null and new."collected" is null)
or (old."collected" is null and new."collected" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'spent')
where old."spent" <> new."spent" 
or (old."spent" is not null and new."spent" is null)
or (old."spent" is null and new."spent" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'remaining')
where old."remaining" <> new."remaining" 
or (old."remaining" is not null and new."remaining" is null)
or (old."remaining" is null and new."remaining" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'event_id')
where old."event_id" <> new."event_id" 
or (old."event_id" is not null and new."event_id" is null)
or (old."event_id" is null and new."event_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'funds',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_funds_delete"
after DELETE on "funds"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'funds',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activities_insert"
after INSERT on "gpoa_activities"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activities',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activities_update"
after UPDATE on "gpoa_activities"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_id')
where old."gpoa_id" <> new."gpoa_id" 
or (old."gpoa_id" is not null and new."gpoa_id" is null)
or (old."gpoa_id" is null and new."gpoa_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'start_date')
where old."start_date" <> new."start_date" 
or (old."start_date" is not null and new."start_date" is null)
or (old."start_date" is null and new."start_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'end_date')
where old."end_date" <> new."end_date" 
or (old."end_date" is not null and new."end_date" is null)
or (old."end_date" is null and new."end_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'objectives')
where old."objectives" <> new."objectives" 
or (old."objectives" is not null and new."objectives" is null)
or (old."objectives" is null and new."objectives" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'participants')
where old."participants" <> new."participants" 
or (old."participants" is not null and new."participants" is null)
or (old."participants" is null and new."participants" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'proposed_budget')
where old."proposed_budget" <> new."proposed_budget" 
or (old."proposed_budget" is not null and new."proposed_budget" is null)
or (old."proposed_budget" is null and new."proposed_budget" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'comments')
where old."comments" <> new."comments" 
or (old."comments" is not null and new."comments" is null)
or (old."comments" is null and new."comments" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'adviser_user_id')
where old."adviser_user_id" <> new."adviser_user_id" 
or (old."adviser_user_id" is not null and new."adviser_user_id" is null)
or (old."adviser_user_id" is null and new."adviser_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'president_user_id')
where old."president_user_id" <> new."president_user_id" 
or (old."president_user_id" is not null and new."president_user_id" is null)
or (old."president_user_id" is null and new."president_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'president_approved_at')
where old."president_approved_at" <> new."president_approved_at" 
or (old."president_approved_at" is not null and new."president_approved_at" is null)
or (old."president_approved_at" is null and new."president_approved_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'adviser_approved_at')
where old."adviser_approved_at" <> new."adviser_approved_at" 
or (old."adviser_approved_at" is not null and new."adviser_approved_at" is null)
or (old."adviser_approved_at" is null and new."adviser_approved_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'rejected_at')
where old."rejected_at" <> new."rejected_at" 
or (old."rejected_at" is not null and new."rejected_at" is null)
or (old."rejected_at" is null and new."rejected_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'status')
where old."status" <> new."status" 
or (old."status" is not null and new."status" is null)
or (old."status" is null and new."status" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'current_step')
where old."current_step" <> new."current_step" 
or (old."current_step" is not null and new."current_step" is null)
or (old."current_step" is null and new."current_step" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'number_of_participants')
where old."number_of_participants" <> new."number_of_participants" 
or (old."number_of_participants" is not null and new."number_of_participants" is null)
or (old."number_of_participants" is null and new."number_of_participants" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_fund_source_id')
where old."gpoa_activity_fund_source_id" <> new."gpoa_activity_fund_source_id" 
or (old."gpoa_activity_fund_source_id" is not null and new."gpoa_activity_fund_source_id" is null)
or (old."gpoa_activity_fund_source_id" is null and new."gpoa_activity_fund_source_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_partnership_type_id')
where old."gpoa_activity_partnership_type_id" <> new."gpoa_activity_partnership_type_id" 
or (old."gpoa_activity_partnership_type_id" is not null and new."gpoa_activity_partnership_type_id" is null)
or (old."gpoa_activity_partnership_type_id" is null and new."gpoa_activity_partnership_type_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_mode_id')
where old."gpoa_activity_mode_id" <> new."gpoa_activity_mode_id" 
or (old."gpoa_activity_mode_id" is not null and new."gpoa_activity_mode_id" is null)
or (old."gpoa_activity_mode_id" is null and new."gpoa_activity_mode_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_type_id')
where old."gpoa_activity_type_id" <> new."gpoa_activity_type_id" 
or (old."gpoa_activity_type_id" is not null and new."gpoa_activity_type_id" is null)
or (old."gpoa_activity_type_id" is null and new."gpoa_activity_type_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'officers_submitted_at')
where old."officers_submitted_at" <> new."officers_submitted_at" 
or (old."officers_submitted_at" is not null and new."officers_submitted_at" is null)
or (old."officers_submitted_at" is null and new."officers_submitted_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'president_submitted_at')
where old."president_submitted_at" <> new."president_submitted_at" 
or (old."president_submitted_at" is not null and new."president_submitted_at" is null)
or (old."president_submitted_at" is null and new."president_submitted_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'president_returned_at')
where old."president_returned_at" <> new."president_returned_at" 
or (old."president_returned_at" is not null and new."president_returned_at" is null)
or (old."president_returned_at" is null and new."president_returned_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'adviser_returned_at')
where old."adviser_returned_at" <> new."adviser_returned_at" 
or (old."adviser_returned_at" is not null and new."adviser_returned_at" is null)
or (old."adviser_returned_at" is null and new."adviser_returned_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activities',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activities_delete"
after DELETE on "gpoa_activities"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activities',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_authors_insert"
after INSERT on "gpoa_activity_authors"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_authors',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_authors_update"
after UPDATE on "gpoa_activity_authors"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_id')
where old."gpoa_activity_id" <> new."gpoa_activity_id" 
or (old."gpoa_activity_id" is not null and new."gpoa_activity_id" is null)
or (old."gpoa_activity_id" is null and new."gpoa_activity_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'officer_user_id')
where old."officer_user_id" <> new."officer_user_id" 
or (old."officer_user_id" is not null and new."officer_user_id" is null)
or (old."officer_user_id" is null and new."officer_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'role')
where old."role" <> new."role" 
or (old."role" is not null and new."role" is null)
or (old."role" is null and new."role" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_authors',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_authors_delete"
after DELETE on "gpoa_activity_authors"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_authors',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_event_heads_insert"
after INSERT on "gpoa_activity_event_heads"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_event_heads',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_event_heads_update"
after UPDATE on "gpoa_activity_event_heads"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_id')
where old."gpoa_activity_id" <> new."gpoa_activity_id" 
or (old."gpoa_activity_id" is not null and new."gpoa_activity_id" is null)
or (old."gpoa_activity_id" is null and new."gpoa_activity_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'role')
where old."role" <> new."role" 
or (old."role" is not null and new."role" is null)
or (old."role" is null and new."role" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_event_heads',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_event_heads_delete"
after DELETE on "gpoa_activity_event_heads"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_event_heads',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_fund_sources_insert"
after INSERT on "gpoa_activity_fund_sources"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_fund_sources',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_fund_sources_update"
after UPDATE on "gpoa_activity_fund_sources"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_fund_sources',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_fund_sources_delete"
after DELETE on "gpoa_activity_fund_sources"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_fund_sources',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_modes_insert"
after INSERT on "gpoa_activity_modes"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_modes',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_modes_update"
after UPDATE on "gpoa_activity_modes"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_modes',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_modes_delete"
after DELETE on "gpoa_activity_modes"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_modes',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_participants_insert"
after INSERT on "gpoa_activity_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_participants_update"
after UPDATE on "gpoa_activity_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_year_id')
where old."student_year_id" <> new."student_year_id" 
or (old."student_year_id" is not null and new."student_year_id" is null)
or (old."student_year_id" is null and new."student_year_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'gpoa_activity_id')
where old."gpoa_activity_id" <> new."gpoa_activity_id" 
or (old."gpoa_activity_id" is not null and new."gpoa_activity_id" is null)
or (old."gpoa_activity_id" is null and new."gpoa_activity_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_participants_delete"
after DELETE on "gpoa_activity_participants"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_participants',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_partnership_types_insert"
after INSERT on "gpoa_activity_partnership_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_partnership_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_partnership_types_update"
after UPDATE on "gpoa_activity_partnership_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_partnership_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_partnership_types_delete"
after DELETE on "gpoa_activity_partnership_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_partnership_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_types_insert"
after INSERT on "gpoa_activity_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoa_activity_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_types_update"
after UPDATE on "gpoa_activity_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoa_activity_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoa_activity_types_delete"
after DELETE on "gpoa_activity_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoa_activity_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoas_insert"
after INSERT on "gpoas"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'gpoas',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoas_update"
after UPDATE on "gpoas"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'academic_period_id')
where old."academic_period_id" <> new."academic_period_id" 
or (old."academic_period_id" is not null and new."academic_period_id" is null)
or (old."academic_period_id" is null and new."academic_period_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'creator_user_id')
where old."creator_user_id" <> new."creator_user_id" 
or (old."creator_user_id" is not null and new."creator_user_id" is null)
or (old."creator_user_id" is null and new."creator_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'report_filepath')
where old."report_filepath" <> new."report_filepath" 
or (old."report_filepath" is not null and new."report_filepath" is null)
or (old."report_filepath" is null and new."report_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'accom_report_filepath')
where old."accom_report_filepath" <> new."accom_report_filepath" 
or (old."accom_report_filepath" is not null and new."accom_report_filepath" is null)
or (old."accom_report_filepath" is null and new."accom_report_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'closed_at')
where old."closed_at" <> new."closed_at" 
or (old."closed_at" is not null and new."closed_at" is null)
or (old."closed_at" is null and new."closed_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'closer_user_id')
where old."closer_user_id" <> new."closer_user_id" 
or (old."closer_user_id" is not null and new."closer_user_id" is null)
or (old."closer_user_id" is null and new."closer_user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'report_file_updated')
where old."report_file_updated" <> new."report_file_updated" 
or (old."report_file_updated" is not null and new."report_file_updated" is null)
or (old."report_file_updated" is null and new."report_file_updated" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'report_file_updated_at')
where old."report_file_updated_at" <> new."report_file_updated_at" 
or (old."report_file_updated_at" is not null and new."report_file_updated_at" is null)
or (old."report_file_updated_at" is null and new."report_file_updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'gpoas',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_gpoas_delete"
after DELETE on "gpoas"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'gpoas',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_meetings_insert"
after INSERT on "meetings"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'meetings',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_meetings_update"
after UPDATE on "meetings"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'title')
where old."title" <> new."title" 
or (old."title" is not null and new."title" is null)
or (old."title" is null and new."title" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'date')
where old."date" <> new."date" 
or (old."date" is not null and new."date" is null)
or (old."date" is null and new."date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'venue')
where old."venue" <> new."venue" 
or (old."venue" is not null and new."venue" is null)
or (old."venue" is null and new."venue" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'agenda')
where old."agenda" <> new."agenda" 
or (old."agenda" is not null and new."agenda" is null)
or (old."agenda" is null and new."agenda" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'participants')
where old."participants" <> new."participants" 
or (old."participants" is not null and new."participants" is null)
or (old."participants" is null and new."participants" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'minutes_file')
where old."minutes_file" <> new."minutes_file" 
or (old."minutes_file" is not null and new."minutes_file" is null)
or (old."minutes_file" is null and new."minutes_file" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'meetings',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_meetings_delete"
after DELETE on "meetings"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'meetings',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_partnerships_insert"
after INSERT on "partnerships"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'partnerships',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_partnerships_update"
after UPDATE on "partnerships"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'organization_name')
where old."organization_name" <> new."organization_name" 
or (old."organization_name" is not null and new."organization_name" is null)
or (old."organization_name" is null and new."organization_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'purpose')
where old."purpose" <> new."purpose" 
or (old."purpose" is not null and new."purpose" is null)
or (old."purpose" is null and new."purpose" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'benefits')
where old."benefits" <> new."benefits" 
or (old."benefits" is not null and new."benefits" is null)
or (old."benefits" is null and new."benefits" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'action')
where old."action" <> new."action" 
or (old."action" is not null and new."action" is null)
or (old."action" is null and new."action" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'links')
where old."links" <> new."links" 
or (old."links" is not null and new."links" is null)
or (old."links" is null and new."links" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'accomplished_by')
where old."accomplished_by" <> new."accomplished_by" 
or (old."accomplished_by" is not null and new."accomplished_by" is null)
or (old."accomplished_by" is null and new."accomplished_by" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'officer')
where old."officer" <> new."officer" 
or (old."officer" is not null and new."officer" is null)
or (old."officer" is null and new."officer" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'partnerships',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_partnerships_delete"
after DELETE on "partnerships"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'partnerships',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_permissions_insert"
after INSERT on "permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_permissions_update"
after UPDATE on "permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'resource_type_id')
where old."resource_type_id" <> new."resource_type_id" 
or (old."resource_type_id" is not null and new."resource_type_id" is null)
or (old."resource_type_id" is null and new."resource_type_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'resource_action_type_id')
where old."resource_action_type_id" <> new."resource_action_type_id" 
or (old."resource_action_type_id" is not null and new."resource_action_type_id" is null)
or (old."resource_action_type_id" is null and new."resource_action_type_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_permissions_delete"
after DELETE on "permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_platforms_insert"
after INSERT on "platforms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'platforms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_platforms_update"
after UPDATE on "platforms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'description')
where old."description" <> new."description" 
or (old."description" is not null and new."description" is null)
or (old."description" is null and new."description" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'start_date')
where old."start_date" <> new."start_date" 
or (old."start_date" is not null and new."start_date" is null)
or (old."start_date" is null and new."start_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'end_date')
where old."end_date" <> new."end_date" 
or (old."end_date" is not null and new."end_date" is null)
or (old."end_date" is null and new."end_date" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'progress')
where old."progress" <> new."progress" 
or (old."progress" is not null and new."progress" is null)
or (old."progress" is null and new."progress" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'platforms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_platforms_delete"
after DELETE on "platforms"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'platforms',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_categories_insert"
after INSERT on "position_categories"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'position_categories',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_categories_update"
after UPDATE on "position_categories"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'position_categories',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_categories_delete"
after DELETE on "position_categories"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'position_categories',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_category_insert"
after INSERT on "position_category"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'position_category',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_category_update"
after UPDATE on "position_category"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_id')
where old."position_id" <> new."position_id" 
or (old."position_id" is not null and new."position_id" is null)
or (old."position_id" is null and new."position_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_category_id')
where old."position_category_id" <> new."position_category_id" 
or (old."position_category_id" is not null and new."position_category_id" is null)
or (old."position_category_id" is null and new."position_category_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'position_category',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_category_delete"
after DELETE on "position_category"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'position_category',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_permissions_insert"
after INSERT on "position_permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'position_permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_permissions_update"
after UPDATE on "position_permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_id')
where old."position_id" <> new."position_id" 
or (old."position_id" is not null and new."position_id" is null)
or (old."position_id" is null and new."position_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'permission_id')
where old."permission_id" <> new."permission_id" 
or (old."permission_id" is not null and new."permission_id" is null)
or (old."permission_id" is null and new."permission_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'position_permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_position_permissions_delete"
after DELETE on "position_permissions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'position_permissions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_positions_insert"
after INSERT on "positions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'positions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_positions_update"
after UPDATE on "positions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_order')
where old."position_order" <> new."position_order" 
or (old."position_order" is not null and new."position_order" is null)
or (old."position_order" is null and new."position_order" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'positions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_positions_delete"
after DELETE on "positions"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'positions',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_action_types_insert"
after INSERT on "resource_action_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'resource_action_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_action_types_update"
after UPDATE on "resource_action_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'resource_action_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_action_types_delete"
after DELETE on "resource_action_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'resource_action_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_types_insert"
after INSERT on "resource_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'resource_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_types_update"
after UPDATE on "resource_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'resource_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_resource_types_delete"
after DELETE on "resource_types"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'resource_types',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_roles_insert"
after INSERT on "roles"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'roles',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_roles_update"
after UPDATE on "roles"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'name')
where old."name" <> new."name" 
or (old."name" is not null and new."name" is null)
or (old."name" is null and new."name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'roles',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_roles_delete"
after DELETE on "roles"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'roles',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_signup_invitations_insert"
after INSERT on "signup_invitations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'signup_invitations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_signup_invitations_update"
after UPDATE on "signup_invitations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'invite_code')
where old."invite_code" <> new."invite_code" 
or (old."invite_code" is not null and new."invite_code" is null)
or (old."invite_code" is null and new."invite_code" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email')
where old."email" <> new."email" 
or (old."email" is not null and new."email" is null)
or (old."email" is null and new."email" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_id')
where old."position_id" <> new."position_id" 
or (old."position_id" is not null and new."position_id" is null)
or (old."position_id" is null and new."position_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'is_accepted')
where old."is_accepted" <> new."is_accepted" 
or (old."is_accepted" is not null and new."is_accepted" is null)
or (old."is_accepted" is null and new."is_accepted" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'expires_at')
where old."expires_at" <> new."expires_at" 
or (old."expires_at" is not null and new."expires_at" is null)
or (old."expires_at" is null and new."expires_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email_sent')
where old."email_sent" <> new."email_sent" 
or (old."email_sent" is not null and new."email_sent" is null)
or (old."email_sent" is null and new."email_sent" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'signup_invitations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_signup_invitations_delete"
after DELETE on "signup_invitations"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'signup_invitations',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_sections_insert"
after INSERT on "student_sections"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'student_sections',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_sections_update"
after UPDATE on "student_sections"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'section')
where old."section" <> new."section" 
or (old."section" is not null and new."section" is null)
or (old."section" is null and new."section" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'student_sections',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_sections_delete"
after DELETE on "student_sections"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'student_sections',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_years_insert"
after INSERT on "student_years"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'student_years',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_years_update"
after UPDATE on "student_years"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'year')
where old."year" <> new."year" 
or (old."year" is not null and new."year" is null)
or (old."year" is null and new."year" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'label')
where old."label" <> new."label" 
or (old."label" is not null and new."label" is null)
or (old."label" is null and new."label" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'student_years',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_student_years_delete"
after DELETE on "student_years"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'student_years',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_students_insert"
after INSERT on "students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_students_update"
after UPDATE on "students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_id')
where old."student_id" <> new."student_id" 
or (old."student_id" is not null and new."student_id" is null)
or (old."student_id" is null and new."student_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'first_name')
where old."first_name" <> new."first_name" 
or (old."first_name" is not null and new."first_name" is null)
or (old."first_name" is null and new."first_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'middle_name')
where old."middle_name" <> new."middle_name" 
or (old."middle_name" is not null and new."middle_name" is null)
or (old."middle_name" is null and new."middle_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'last_name')
where old."last_name" <> new."last_name" 
or (old."last_name" is not null and new."last_name" is null)
or (old."last_name" is null and new."last_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'suffix_name')
where old."suffix_name" <> new."suffix_name" 
or (old."suffix_name" is not null and new."suffix_name" is null)
or (old."suffix_name" is null and new."suffix_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'course_id')
where old."course_id" <> new."course_id" 
or (old."course_id" is not null and new."course_id" is null)
or (old."course_id" is null and new."course_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_year_id')
where old."student_year_id" <> new."student_year_id" 
or (old."student_year_id" is not null and new."student_year_id" is null)
or (old."student_year_id" is null and new."student_year_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'student_section_id')
where old."student_section_id" <> new."student_section_id" 
or (old."student_section_id" is not null and new."student_section_id" is null)
or (old."student_section_id" is null and new."student_section_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email')
where old."email" <> new."email" 
or (old."email" is not null and new."email" is null)
or (old."email" is null and new."email" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_students_delete"
after DELETE on "students"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'students',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_user_google_accounts_insert"
after INSERT on "user_google_accounts"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'user_google_accounts',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_user_google_accounts_update"
after UPDATE on "user_google_accounts"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'user_id')
where old."user_id" <> new."user_id" 
or (old."user_id" is not null and new."user_id" is null)
or (old."user_id" is null and new."user_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'google_id')
where old."google_id" <> new."google_id" 
or (old."google_id" is not null and new."google_id" is null)
or (old."google_id" is null and new."google_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'token')
where old."token" <> new."token" 
or (old."token" is not null and new."token" is null)
or (old."token" is null and new."token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'refresh_token')
where old."refresh_token" <> new."refresh_token" 
or (old."refresh_token" is not null and new."refresh_token" is null)
or (old."refresh_token" is null and new."refresh_token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'expires_at')
where old."expires_at" <> new."expires_at" 
or (old."expires_at" is not null and new."expires_at" is null)
or (old."expires_at" is null and new."expires_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'user_google_accounts',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_user_google_accounts_delete"
after DELETE on "user_google_accounts"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'user_google_accounts',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_users_insert"
after INSERT on "users"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'insert',
  table_name = 'users',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = new.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_users_update"
after UPDATE on "users"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols")
values ('');
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'id')
where old."id" <> new."id" 
or (old."id" is not null and new."id" is null)
or (old."id" is null and new."id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'remember_token')
where old."remember_token" <> new."remember_token" 
or (old."remember_token" is not null and new."remember_token" is null)
or (old."remember_token" is null and new."remember_token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'created_at')
where old."created_at" <> new."created_at" 
or (old."created_at" is not null and new."created_at" is null)
or (old."created_at" is null and new."created_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'updated_at')
where old."updated_at" <> new."updated_at" 
or (old."updated_at" is not null and new."updated_at" is null)
or (old."updated_at" is null and new."updated_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'first_name')
where old."first_name" <> new."first_name" 
or (old."first_name" is not null and new."first_name" is null)
or (old."first_name" is null and new."first_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'middle_name')
where old."middle_name" <> new."middle_name" 
or (old."middle_name" is not null and new."middle_name" is null)
or (old."middle_name" is null and new."middle_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'last_name')
where old."last_name" <> new."last_name" 
or (old."last_name" is not null and new."last_name" is null)
or (old."last_name" is null and new."last_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'suffix_name')
where old."suffix_name" <> new."suffix_name" 
or (old."suffix_name" is not null and new."suffix_name" is null)
or (old."suffix_name" is null and new."suffix_name" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email')
where old."email" <> new."email" 
or (old."email" is not null and new."email" is null)
or (old."email" is null and new."email" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'role_id')
where old."role_id" <> new."role_id" 
or (old."role_id" is not null and new."role_id" is null)
or (old."role_id" is null and new."role_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'position_id')
where old."position_id" <> new."position_id" 
or (old."position_id" is not null and new."position_id" is null)
or (old."position_id" is null and new."position_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'username')
where old."username" <> new."username" 
or (old."username" is not null and new."username" is null)
or (old."username" is null and new."username" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'password')
where old."password" <> new."password" 
or (old."password" is not null and new."password" is null)
or (old."password" is null and new."password" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'avatar_filepath')
where old."avatar_filepath" <> new."avatar_filepath" 
or (old."avatar_filepath" is not null and new."avatar_filepath" is null)
or (old."avatar_filepath" is null and new."avatar_filepath" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'google_id')
where old."google_id" <> new."google_id" 
or (old."google_id" is not null and new."google_id" is null)
or (old."google_id" is null and new."google_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'google_token')
where old."google_token" <> new."google_token" 
or (old."google_token" is not null and new."google_token" is null)
or (old."google_token" is null and new."google_token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'google_refresh_token')
where old."google_refresh_token" <> new."google_refresh_token" 
or (old."google_refresh_token" is not null and new."google_refresh_token" is null)
or (old."google_refresh_token" is null and new."google_refresh_token" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'google_expires_at')
where old."google_expires_at" <> new."google_expires_at" 
or (old."google_expires_at" is not null and new."google_expires_at" is null)
or (old."google_expires_at" is null and new."google_expires_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'public_id')
where old."public_id" <> new."public_id" 
or (old."public_id" is not null and new."public_id" is null)
or (old."public_id" is null and new."public_id" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'deleted_at')
where old."deleted_at" <> new."deleted_at" 
or (old."deleted_at" is not null and new."deleted_at" is null)
or (old."deleted_at" is null and new."deleted_at" is not null);
update "audit_trigger_variables"
set "changed_cols" = json_array_append(changed_cols, '$', 'email_verified_at')
where old."email_verified_at" <> new."email_verified_at" 
or (old."email_verified_at" is not null and new."email_verified_at" is null)
or (old."email_verified_at" is null and new."email_verified_at" is not null);

  update audit_trail_data
set
  action = 'update',
  table_name = 'users',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;
CREATE TRIGGER "audit_users_delete"
after DELETE on "users"
for each row
begin
  insert into "audit_trigger_variables" ("changed_cols") 
values (null);

  update audit_trail_data
set
  action = 'delete',
  table_name = 'users',
  column_names = (select "changed_cols" from "audit_trigger_variables"),
  primary_key = old.id,
  created_at = now();

  insert into "audit_trail" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_03_12_141818_create_positions_table',1);
INSERT INTO migrations VALUES(5,'2025_03_13_000000_create_roles_table',1);
INSERT INTO migrations VALUES(6,'2025_03_13_000445_update_users_table',1);
INSERT INTO migrations VALUES(7,'2025_03_16_085937_create_events_table',1);
INSERT INTO migrations VALUES(8,'2025_04_05_084804_create_resource_action_types_table',1);
INSERT INTO migrations VALUES(9,'2025_04_05_084805_create_resource_types_table',1);
INSERT INTO migrations VALUES(10,'2025_04_05_084806_create_permissions_table',1);
INSERT INTO migrations VALUES(11,'2025_04_05_095944_create_position_permissions_table',1);
INSERT INTO migrations VALUES(12,'2025_04_18_145337_create_meetings_table',1);
INSERT INTO migrations VALUES(13,'2025_04_21_063950_create_funds_table',1);
INSERT INTO migrations VALUES(14,'2025_04_21_141024_create_platforms_table',1);
INSERT INTO migrations VALUES(15,'2025_04_21_143624_create_partnerships_table',1);
INSERT INTO migrations VALUES(16,'2025_05_12_124248_create_activity_logs_table',1);
INSERT INTO migrations VALUES(17,'2025_05_17_054013_create_courses_table',1);
INSERT INTO migrations VALUES(18,'2025_05_17_055059_create_students_table',1);
INSERT INTO migrations VALUES(19,'2025_05_19_073151_create_events_attendance_table',1);
INSERT INTO migrations VALUES(20,'2025_05_21_123612_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(21,'2025_05_23_070609_create_announcements_table',1);
INSERT INTO migrations VALUES(22,'2025_05_25_104341_create_events_deliverables_table',1);
INSERT INTO migrations VALUES(23,'2025_05_25_104557_create_events_deliverables_tasks_table',1);
INSERT INTO migrations VALUES(24,'2025_05_25_111954_create_events_deliverable_assignee_table',1);
INSERT INTO migrations VALUES(25,'2025_05_26_083644_create_event_editor_table',1);
INSERT INTO migrations VALUES(26,'2025_06_01_095307_create_table_signup_invitations_table',1);
INSERT INTO migrations VALUES(27,'2025_06_02_181147_update_signup_invitations_table',1);
INSERT INTO migrations VALUES(28,'2025_06_15_081514_create_event_dates_table',1);
INSERT INTO migrations VALUES(29,'2025_06_15_085841_update_events_table',1);
INSERT INTO migrations VALUES(30,'2025_06_15_100214_update_event_dates_table',1);
INSERT INTO migrations VALUES(31,'2025_06_20_015814_create_student_years_table',1);
INSERT INTO migrations VALUES(32,'2025_06_20_015828_create_student_sections_table',1);
INSERT INTO migrations VALUES(33,'2025_06_20_021455_update_students_table',1);
INSERT INTO migrations VALUES(34,'2025_06_20_025722_change_columns_in_students_table',1);
INSERT INTO migrations VALUES(35,'2025_06_20_031349_change_columns_in_students_table',1);
INSERT INTO migrations VALUES(36,'2025_06_20_032414_change_columns_in_students_table',1);
INSERT INTO migrations VALUES(37,'2025_06_20_050540_rename_events_attendance_table',1);
INSERT INTO migrations VALUES(38,'2025_06_20_052913_add_columns_to_event_attendances_table',1);
INSERT INTO migrations VALUES(39,'2025_07_03_065749_create_gspoa_table',1);
INSERT INTO migrations VALUES(40,'2025_07_04_011308_change_columns_in_positions_table',1);
INSERT INTO migrations VALUES(41,'2025_07_04_015717_create_gspoa_editors_table',1);
INSERT INTO migrations VALUES(42,'2025_07_04_024622_change_columns_in_gspoas_table',1);
INSERT INTO migrations VALUES(43,'2025_07_07_170122_create_gspoa_events_table',1);
INSERT INTO migrations VALUES(44,'2025_07_07_204806_create_gspoa_event_participants_table',1);
INSERT INTO migrations VALUES(45,'2025_07_07_215310_create_event_participants_table',1);
INSERT INTO migrations VALUES(46,'2025_07_08_043018_change_columns_in_events_table',1);
INSERT INTO migrations VALUES(47,'2025_07_10_080520_create_academic_terms_table',1);
INSERT INTO migrations VALUES(48,'2025_07_10_084419_create_academic_years_table',1);
INSERT INTO migrations VALUES(49,'2025_07_10_091210_create_gpoas_table',1);
INSERT INTO migrations VALUES(50,'2025_07_10_092135_create_gpoa_activities_table',1);
INSERT INTO migrations VALUES(51,'2025_07_10_115812_create_gpoa_activity_authors_table',1);
INSERT INTO migrations VALUES(52,'2025_07_11_061758_create_position_categories_table',1);
INSERT INTO migrations VALUES(53,'2025_07_11_062504_create_position_category_table',1);
INSERT INTO migrations VALUES(54,'2025_07_11_062739_create_gpoa_activity_types_table',1);
INSERT INTO migrations VALUES(55,'2025_07_11_063029_change_columns_in_gpoa_activities_table',1);
INSERT INTO migrations VALUES(56,'2025_07_11_064923_create_gpoa_activity_partnership_types_table',1);
INSERT INTO migrations VALUES(57,'2025_07_11_065026_create_gpoa_activity_fund_sources_table',1);
INSERT INTO migrations VALUES(58,'2025_07_11_065101_create_gpoa_activity_modes_table',1);
INSERT INTO migrations VALUES(59,'2025_07_11_065232_change_columns_in_gpoa_activities_table',1);
INSERT INTO migrations VALUES(60,'2025_07_11_142509_create_gpoa_activity_event_heads_table',1);
INSERT INTO migrations VALUES(61,'2025_07_12_064013_create_gpoa_activity_participants_table',1);
INSERT INTO migrations VALUES(62,'2025_07_12_074426_change_columns_in_student_years_table',1);
INSERT INTO migrations VALUES(63,'2025_07_16_091339_change_columns_in_gpoas_table',2);
INSERT INTO migrations VALUES(64,'2025_07_21_092048_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(65,'2025_07_21_124740_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(66,'2025_07_26_041219_create_event_registrations_table',3);
INSERT INTO migrations VALUES(67,'2025_07_26_080251_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(68,'2025_07_26_080537_change_columns_in_gpoa_activities_table',3);
INSERT INTO migrations VALUES(69,'2025_07_26_080651_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(70,'2025_07_26_080748_change_columns_in_users_table',3);
INSERT INTO migrations VALUES(71,'2025_07_26_081058_change_columns_in_students_table',3);
INSERT INTO migrations VALUES(72,'2025_07_26_140357_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(73,'2025_07_27_035545_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(74,'2025_08_01_043243_change_columns_in_event_attendances_table',3);
INSERT INTO migrations VALUES(75,'2025_08_01_045846_change_columns_in_event_dates_table',3);
INSERT INTO migrations VALUES(76,'2025_08_02_073208_create_event_eval_forms_table',3);
INSERT INTO migrations VALUES(77,'2025_08_04_050935_rename_event_eval_form_questions_table',3);
INSERT INTO migrations VALUES(78,'2025_08_04_055308_change_columns_in_student_years_table',3);
INSERT INTO migrations VALUES(79,'2025_08_06_092625_create_audit_trail_table',3);
INSERT INTO migrations VALUES(80,'2025_08_09_092732_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(81,'2025_08_09_121417_create_event_students_table',3);
INSERT INTO migrations VALUES(82,'2025_08_09_123211_change_columns_in_event_registrations_table',3);
INSERT INTO migrations VALUES(83,'2025_08_11_070859_change_columns_in_event_students_table',3);
INSERT INTO migrations VALUES(84,'2025_08_11_075541_create_event_attendees_table',3);
INSERT INTO migrations VALUES(85,'2025_08_11_082304_create_event_regis_form_table',3);
INSERT INTO migrations VALUES(86,'2025_08_12_090717_create_accom_report_table',3);
INSERT INTO migrations VALUES(87,'2025_08_14_071949_change_columns_in_gpoa_activities_table',3);
INSERT INTO migrations VALUES(88,'2025_08_17_105920_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(89,'2025_08_19_052811_change_columns_in_event_students_table',3);
INSERT INTO migrations VALUES(90,'2025_08_20_032447_create_event_officer_attendees_table',3);
INSERT INTO migrations VALUES(91,'2025_08_20_111415_change_columns_in_positions_table',3);
INSERT INTO migrations VALUES(92,'2025_08_23_082142_create_event_attachments_table',3);
INSERT INTO migrations VALUES(93,'2025_08_23_084348_create_event_attachments_table',3);
INSERT INTO migrations VALUES(94,'2025_09_01_051635_change_columns_in_event_dates_table',3);
INSERT INTO migrations VALUES(95,'2025_09_01_100302_create_event_evaluations_table',3);
INSERT INTO migrations VALUES(96,'2025_09_04_090243_change_columns_in_users_table',3);
INSERT INTO migrations VALUES(97,'2025_09_17_092633_change_columns_in_signup_invitations_table',3);
INSERT INTO migrations VALUES(98,'2025_09_18_024934_change_columns_in_users_table',3);
INSERT INTO migrations VALUES(99,'2025_09_18_062839_change_columns_in_users_table',3);
INSERT INTO migrations VALUES(100,'2025_09_20_054249_create_event_evaluation_tokens_table',3);
INSERT INTO migrations VALUES(101,'2025_09_21_055003_change_columns_in_event_evaluations_table',3);
INSERT INTO migrations VALUES(102,'2025_09_26_121515_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(103,'2025_09_26_123827_change_columns_in_event_attendees_table',3);
INSERT INTO migrations VALUES(104,'2025_09_26_125000_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(105,'2025_09_27_064308_change_columns_in_events_table',3);
INSERT INTO migrations VALUES(106,'2025_09_28_153750_change_columns_in_event_evaluations_table',3);
INSERT INTO migrations VALUES(107,'2025_09_29_084620_change_columns_in_academic_periods_table',3);
INSERT INTO migrations VALUES(108,'2025_09_29_125428_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(109,'2025_10_04_115501_create_user_google_accounts_table',3);
INSERT INTO migrations VALUES(110,'2025_10_07_040747_change_columns_in_student_sections_table',3);
INSERT INTO migrations VALUES(111,'2025_10_07_041240_change_columns_in_student_years_table',3);
INSERT INTO migrations VALUES(112,'2025_10_07_042153_change_columns_in_courses_table',3);
INSERT INTO migrations VALUES(113,'2025_10_07_083908_change_columns_gpoa_activity_modes_table',3);
INSERT INTO migrations VALUES(114,'2025_10_07_085013_change_columns_in_gpoa_activity_types_table',3);
INSERT INTO migrations VALUES(115,'2025_10_07_085302_change_columns_in_gpoa_activity_partnership_types_table',3);
INSERT INTO migrations VALUES(116,'2025_10_07_085527_change_columns_in_gpoa_activity_fund_sources_table',3);
INSERT INTO migrations VALUES(117,'2025_10_13_050617_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(118,'2025_10_16_100614_change_columns_in_gpoas_table',3);
INSERT INTO migrations VALUES(119,'2025_10_17_065642_create_event_participant_courses_table',3);
INSERT INTO migrations VALUES(120,'2025_10_18_030748_change_columns_in_audit_trail_table',3);
INSERT INTO migrations VALUES(121,'2025_10_31_022613_change_columns_in_accom_reports_table',4);
INSERT INTO migrations VALUES(122,'2025_10_31_024218_change_columns_in_accom_reports_table',4);
INSERT INTO migrations VALUES(123,'2025_10_31_024343_change_columns_in_gpoas_table',4);
INSERT INTO migrations VALUES(124,'2025_11_07_015804_change_columns_in_accom_reports_table',4);
INSERT INTO migrations VALUES(125,'2025_11_07_015924_change_columns_in_gpoas_table',4);
INSERT INTO migrations VALUES(126,'2025_11_28_081951_change_columns_in_events_table',4);
INSERT INTO migrations VALUES(127,'2025_11_30_095622_create_event_links_table',4);
INSERT INTO migrations VALUES(128,'2026_01_10_162318_change_columns_in_audit_trail_table',4);
INSERT INTO migrations VALUES(129,'2026_01_10_163253_drop_gspoas_table',4);
