<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsAdmitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_admissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('professional_id');
            $table->integer('patient_id')->nullable();
            $table->integer('account_id');
            $table->timestamps();

            $table->text('background_reason')->nullable();
            $table->text('background_problem_evolution')->nullable();
            $table->text('background_solution_strategy')->nullable();
            $table->text('background_previous_treatments')->nullable();
            $table->text('background_evolutive_history')->nullable();
            $table->text('background_previous_psi_treatments')->nullable();
            $table->text('background_family_genogram')->nullable();
            $table->text('background_medical_personal')->nullable();
            $table->text('interview_synthesis_themes')->nullable();
            $table->text('interview_synthesis_appetite')->nullable();
            $table->text('interview_synthesis_work_study_data')->nullable();
            $table->text('case_formulation')->nullable();
            $table->text('mental_exam_presentation')->nullable();
            $table->text('mental_exam_attention')->nullable();
            $table->text('mental_exam_orientation')->nullable();
            $table->text('mental_exam_memory')->nullable();
            $table->text('mental_exam_thought')->nullable();
            $table->text('mental_exam_thought_perception')->nullable();
            $table->text('mental_exam_mood')->nullable();
            $table->text('mental_exam_engine')->nullable();
            $table->text('other_comments')->nullable();
            $table->text('dsm_diagnostic')->nullable();
            $table->text('dsm_medical_disease')->nullable();
            $table->text('dsm_psychosocial_stress')->nullable();
            $table->text('dsm_operating_level')->nullable();
            $table->text('proposed_treatment_program')->nullable();
            $table->text('indications_evaluation_request')->nullable();
            $table->text('indications_medical_evaluation_request')->nullable();
            $table->text('indications_laboratory')->nullable();
            $table->text('indications_others')->nullable();
            $table->string('family_social_red')->nullable();
            $table->string('family_social_red_expectations')->nullable();
            $table->string('family_social_red_motivation')->nullable();
            $table->string('family_social_red_rectance')->nullable();
            $table->string('family_social_red_coping_style')->nullable();
            $table->string('data_n_trat_prev_psic')->nullable();
            $table->string('data_n_trat_prev_psiq')->nullable();
            $table->string('data_n_ts')->nullable();
            $table->string('data_idea_s_um')->nullable();
            $table->string('data_n_interm_clin')->nullable();
            $table->string('data_n_int_psic')->nullable();
            $table->string('data_n_d_int_psic')->nullable();
            $table->string('data_n_d_emer_psiq')->nullable();
            $table->string('data_n_ingesta_atrac_um')->nullable();
            $table->string('data_n_autoles_um')->nullable();
            $table->string('data_n_e_vcia_verbal_um')->nullable();
            $table->string('data_n_e_vcia_fis_um')->nullable();
            $table->string('data_n_d_c_cons_ol_um')->nullable();
            $table->string('data_n_c_cons_drogas_um')->nullable();
            $table->string('data_n_d_c_cons_med_c_um')->nullable();
            $table->string('data_n_mentiras')->nullable();
            $table->string('data_robos_intra_fliar')->nullable();
            $table->string('data_robos_extra_fliar')->nullable();
            $table->string('data_n_d_aus_clases_trab')->nullable();
            $table->string('data_edad_com_1_trat')->nullable();
            $table->string('data_c_autoreporte')->nullable();
            $table->string('data_s_autoreporte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patients_admitions');
    }
}
