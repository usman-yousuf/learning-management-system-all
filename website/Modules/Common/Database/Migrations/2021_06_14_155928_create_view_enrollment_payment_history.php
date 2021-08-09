<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewEnrollmentPaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS enrollment_payment_history;");
        // DB::statement("
        //     CREATE VIEW enrollment_payment_history AS
        //     (
        //         SELECT

        //             teacher.id AS teacher_id
        //             , teacher.uuid  AS teacher_uuid
        //             , teacher.first_name AS teacher_first_name
        //             , teacher.last_name AS teacher_last_name
        //             , teacher.gender AS teacher_gender
        //             , teacher.profile_image AS teacher_profile_image
        //             , teacher.dob AS teacher_dob
        //             , teacher.interests AS teacher_interests
        //             , teacher.phone_code AS teacher_phone_code
        //             , teacher.phone_number AS teacher_phone_number
        //             , teacher.phone_verified_at AS teacher_phone_verified_at
        //             , teacher.phone_code_2 AS teacher_phone_code_2
        //             , teacher.phone_number_2 AS teacher_phone_number_2
        //             , teacher.phone_2_verified_at AS teacher_phone_2_verified_at
        //             , teacher.status AS teacher_status
        //             , teacher.deleted_at AS teacher_deleted_at
        //             , teacher.created_at AS teacher_created_at
        //             , teacher.updated_at AS teacher_updated_at



        //             , student.id AS student_id
        //             , student.uuid AS student_uuid
        //             , student.first_name AS student_first_name
        //             , student.last_name AS student_last_name
        //             , student.gender AS student_gender
        //             , student.profile_image AS student_profile_image
        //             , student.dob AS student_dob
        //             , student.interests AS student_interests
        //             , student.phone_code AS student_phone_code
        //             , student.phone_number AS student_phone_number
        //             , student.phone_verified_at AS student_phone_verified_at
        //             , student.phone_code_2 AS student_phone_code_2
        //             , student.phone_number_2 AS student_phone_number_2
        //             , student.phone_2_verified_at AS student_phone_2_verified_at
        //             , student.status AS student_status
        //             , student.deleted_at AS student_deleted_at
        //             , student.created_at AS student_created_at
        //             , student.updated_at AS student_updated_at


        //             , ph.id AS payment_history_id
        //             , ph.uuid AS payment_history_uuid
        //             , ph.ref_model_name AS payment_history_ref_model
        //             , ph.ref_id AS payment_history_ref_model_id
        //             , ph.additional_ref_model_name AS payment_history_additional_ref_model
        //             , ph.additional_ref_id AS payment_history_additional_ref_model_id
        //             , ph.stripe_trans_id AS payment_stripe_transaction_id
        //             , ph.stripe_trans_status AS payment_stripe_transaction_status
        //             , ph.stripe_card_id AS payment_history_stripe_card_id
        //             , ph.easypaisa_trans_id AS payment_easypaisa_transaction_id
        //             , ph.easypaisa_trans_status AS payment_easypaisa_transaction_status
        //             , ph.payment_method AS payment_method
        //             , ph.payee_id AS payee_id
        //             , ph.status AS payment_history_status
        //             , ph.deleted_at AS payment_history_deleted_at
        //             , ph.created_at AS payment_history_created_at
        //             , ph.updated_at AS payment_history_updated_at


        //             , enrollment.id AS enrollment_id
        //             , enrollment.uuid AS enroll_uuid
        //             , enrollment.course_id AS enroll_course_id
        //             , enrollment.student_id AS enroll_student_id
        //             , enrollment.slot_id AS enroll_slot_id
        //             , enrollment.status AS enroll_status
        //             , enrollment.joining_date AS enroll_joining_date
        //             , enrollment.deleted_at AS enroll_deleted_at
        //             , enrollment.created_at AS enroll_created_at
        //             , enrollment.updated_at AS enroll_updated_at


        //             , crs_cat.id AS course_categories_id
        //             , crs_cat.uuid AS crs_cat_uuid
        //             , crs_cat.name AS crs_cat_name
        //             , crs_cat.description AS crs_cat_description
        //             , crs_cat.deleted_at AS crs_cat_deleted_at
        //             , crs_cat.created_at AS crs_cat_created_at
        //             , crs_cat.updated_at AS crs_cat_updated_at


        //             , teacher_meta.id AS teacher_meta_id
        //             , teacher_meta.uuid AS t_meta_uuid
        //             , teacher_meta.profile_id AS t_profile_id
        //             , teacher_meta.total_rating_count AS t_total_rating_count
        //             , teacher_meta.total_raters_count AS t_total_raters_count
        //             , teacher_meta.total_courses_count AS t_total_courses_count
        //             , teacher_meta.total_completed_courses_count AS t_total_completed_courses_count
        //             , teacher_meta.total_cancelled_courses_count AS t_total_cancelled_courses_count
        //             , teacher_meta.total_chats_count AS t_total_chats_count
        //             , teacher_meta.deleted_at AS t_deleted_at
        //             , teacher_meta.created_at AS t_created_at
        //             , teacher_meta.updated_at AS t_updated_at


        //             , student_meta.id AS student_meta_id
        //             , student_meta.uuid AS s_meta_uuid
        //             , student_meta.profile_id AS s_profile_id
        //             , student_meta.total_rating_count AS s_total_rating_count
        //             , student_meta.total_raters_count AS s_total_raters_count
        //             , student_meta.total_courses_count AS s_total_courses_count
        //             , student_meta.total_completed_courses_count AS s_total_completed_courses_count
        //             , student_meta.total_cancelled_courses_count AS s_total_cancelled_courses_count
        //             , student_meta.total_chats_count AS s_total_chats_count
        //             , student_meta.deleted_at AS s_deleted_at
        //             , student_meta.created_at AS s_created_at
        //             , student_meta.updated_at AS s_updated_at


        //             , slots.id AS slots_id
        //             , slots.uuid AS sl_uuid
        //             , slots.course_id AS sl_course_id
        //             , slots.slot_start AS sl_slot_start
        //             , slots.slot_end AS sl_slot_end
        //             , slots.day_nums AS sl_day_nums
        //             , slots.deleted_at AS sl_deleted_at
        //             , slots.created_at AS sl_created_at
        //             , slots.updated_at AS sl_updated_at


        //             , stripe_cards.id AS stripe_cards_id
        //             , stripe_cards.uuid AS str_card_uuid
        //             , stripe_cards.card_holder_id AS str_card_holder_id
        //             , stripe_cards.stripe_card_id AS str_card_id
        //             , stripe_cards.card_holder_name AS str_card_holder_name
        //             , stripe_cards.brand AS str_brand
        //             , stripe_cards.last4 AS str_last4
        //             , stripe_cards.exp_month AS str_exp_month
        //             , stripe_cards.exp_year AS str_exp_year
        //             , stripe_cards.country AS str_country
        //             , stripe_cards.is_default AS str_is_default
        //             , stripe_cards.deleted_at AS str_deleted_at
        //             , stripe_cards.created_at AS str_created_at
        //             , stripe_cards.updated_at AS str_updated_at


        //         FROM
        //         payment_histories AS ph
        //             INNER JOIN (
        //                 student_courses AS enrollment
        //                 INNER JOIN
        //                 (
        //                     courses AS crs
        //                         INNER JOIN course_categories AS crs_cat
        //                         ON crs.course_category_id = crs_cat.id

        //                         INNER JOIN profiles AS teacher
        //                             left JOIN profile_metas as teacher_meta
        //                             ON teacher.id = teacher_meta.profile_id
        //                         ON crs.teacher_id = teacher.id
        //                 )
        //                 ON enrollment.course_id = crs.id

        //                 INNER JOIN course_slots AS slots ON enrollment.slot_id = slots.id
        //                 INNER JOIN
        //                 (
        //                     profiles as student
        //                         left JOIN profile_metas as student_meta
        //                         ON student.id = student_meta.profile_id
        //                 )
        //                 ON enrollment.student_id = student.id
        //             )
        //             ON ph.ref_id = enrollment.id AND ph.ref_model_name = 'student_courses'

        //             LEFT JOIN cards AS stripe_cards ON ph.stripe_card_id = stripe_cards.id


        //         ORDER BY
        //         ph.created_at DESC
        //         , enrollment.created_at DESC
        //         , crs.created_at DESC
        //         , crs.created_at DESC
        //     );
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS enrollment_payment_history;");
    }
}
