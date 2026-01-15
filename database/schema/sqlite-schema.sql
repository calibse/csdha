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
