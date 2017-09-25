<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientAdmision extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'background_reason',
		'background_problem_evolution',
		'background_solution_strategy',
		'background_previous_treatments',
		'background_evolutive_history',
		'background_previous_psi_treatments',
		'background_family_genogram',
		'background_medical_personal',
		'background_medical_family',
		'interview_synthesis_themes',
		'interview_synthesis_appetite',
		'interview_synthesis_work_study_data',
		'mental_exam_presentation',
		'mental_exam_attention',
		'mental_exam_orientation',
		'mental_exam_memory',
		'mental_exam_thought',
		'mental_exam_thought_perception',
		'mental_exam_mood',
		'mental_exam_engine',
		'other_comments',
		'dsm_diagnostic',
		'dsm_medical_disease',
		'dsm_psychosocial_stress',
		'dsm_operating_level',
		'proposed_treatment_program',
		'indications_evaluation_request',
		'indications_medical_evaluation_request',
		'indications_laboratory',
		'indications_others',
		'family_social_red',
		'family_social_red_expectations',
		'family_social_red_motivation',
		'family_social_red_rectance',
		'family_social_red_coping_style',
		'data_n_trat_prev_psic',
		'data_n_trat_prev_psiq',
		'data_n_ts',
		'data_idea_s_um',
		'data_n_interm_clin',
		'data_n_int_psic',
		'data_n_d_int_psic',
		'data_n_d_emer_psiq',
		'data_n_ingesta_atrac_um',
		'data_n_autoles_um',
		'data_n_e_vcia_verbal_um',
		'data_n_e_vcia_fis_um',
		'data_n_d_c_cons_ol_um',
		'data_n_c_cons_drogas_um',
		'data_n_d_c_cons_med_c_um',
		'data_n_mentiras',
		'data_robos_intra_fliar',
		'data_robos_extra_fliar',
		'data_n_d_aus_clases_trab',
		'data_edad_com_1_trat',
		'data_c_autoreporte',
		'data_s_autoreporte',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	* Get the patient that owns the admision.
	*/
	public function patient()
	{
		return $this->belongsTo('App\Patient');
	}
}
