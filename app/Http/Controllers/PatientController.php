<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Patient;
use App\Professional;
use App\PatientAdmision;
use App\HCDate;
use Auth;
use File;
use Storage;

class PatientController extends AdminController
{
	private $patientData = [
		'Datos del paciente' => [
			'Personales' => [
				'patient_firstname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'required|string|max:50',
				],
				'patient_lastname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'required|string|max:50',
				],
				'patient_document_number' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Nº de documento',
					'validation' => 'required|integer',
				],
				'patient_document_type' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Tipo de documento',
					'validation' => 'required|string|max:10',
				],
				'patient_gender' => [
					'css_class' => 'col-1-6',
					'type' => 'select',
					'title' => 'Género',
					'options' => [
						['id' => 'hombre', 'value' => 'Hombre'],
						['id' => 'mujer', 'value' => 'Mujer'],
						['id' => 'transexual', 'value' => 'Transexual'],
					],
					'validation' => 'in:hombre,mujer,transexual',
				],
				'patient_birthdate' => [
					'css_class' => 'col-1-4',
					'type' => 'inputDate',
					'title' => 'Fecha de nacimiento',
					'validation' => 'date',
				],
				'patient_nationality' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Nacionalidad',
					'validation' => 'string|max:50',
				],
				'patient_phone' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
				'patient_cellphone' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'required|string|max:20',
				],
				'patient_state' => [
					'css_class' => 'col-1-6',
					'type' => 'select',
					'title' => 'Habilitado',
					'options' => [
						['id' => 1, 'value' => 'Si'],
						['id' => 0, 'value' => 'No'],
					],
					'validation' => 'boolean',
				],
			],
			'Sistema de salud' => [
				'patient_medical_coverage' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Cobertura médica',
					'validation' => 'string|max:20',
				],
				'patient_medical_coverage_plan' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Plan',
					'validation' => 'string|max:20',
				],
				'patient_medical_coverage_number' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Número',
					'validation' => 'string|max:50',
				],
			],
			'Otros' => [
				'patient_studies' => [
					'css_class' => 'col-1-4',
					'type' => 'select',
					'title' => 'Estudios',
					'options' => [
						['id' => 'ninguno', 'value' => 'Ninguno'],
						['id' => 'primarios', 'value' => 'Primarios'],
						['id' => 'secundarios', 'value' => 'Secundarios'],
						['id' => 'terciarios', 'value' => 'Terciarios'],
						['id' => 'universitarios', 'value' => 'Universitarios'],
					],
					'validation' => 'in:ninguno,primarios,secundarios,terciarios,universitarios',
				],
				'patient_complete_studies' => [
					'css_class' => 'col-1-4',
					'type' => 'select',
					'title' => 'Completos',
					'options' => [
						['id' => 1, 'value' => 'Si'],
						['id' => 0, 'value' => 'No'],
					],
					'validation' => 'boolean',
				],
				'patient_ocupation' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Ocupación',
					'validation' => 'string|max:50',
				],
				'patient_civil_status' => [
					'css_class' => 'col-1-4',
					'type' => 'select',
					'options' => [
						['id' => 'soltero', 'value' => 'Soltero/a'],
						['id' => 'casado', 'value' => 'Casado/a'],
						['id' => 'divorciado', 'value' => 'Divorciado/a'],
						['id' => 'viudo', 'value' => 'Viudo/a'],
					],
					'title' => 'Estado civil',
					'validation' => 'in:soltero,casado,divorciado,viudo',
				],
			],
			'Emails' => [
				'patient_email_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputEmail',
					'title' => 'Email',
					'validation' => 'string|max:250',
				],
				'patient_email_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputEmail',
					'title' => 'Email',
					'validation' => 'string|max:250',
				],
				'patient_email_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputEmail',
					'title' => 'Email',
					'validation' => 'string|max:250',
				],
			],
		],
		'Datos del consultante' => [
			'Personales' => [
				'consultant_firstname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'consultant_lastname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'consultant_relationship' => [
					'css_class' => 'col-1-4',
					'type' => 'select',
					'title' => 'Relación',
					'options' => [
						['id' => 'allegado', 'value' => 'Allegado/a'],
						['id' => 'pariente', 'value' => 'Pariente'],
						['id' => 'padre', 'value' => 'Padre'],
						['id' => 'madre', 'value' => 'Madre'],
					],
					'validation' => 'in:allegado,pariente,padre_madre',
				],
			],
			'Dirección' => [
				'consultant_street' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Calle',
					'validation' => 'string|max:50',
				],
				'consultant_number' => [
					'css_class' => 'col-1-12',
					'type' => 'inputText',
					'title' => 'Número',
					'validation' => 'string',
				],
				'consultant_flat' => [
					'css_class' => 'col-1-12',
					'type' => 'inputText',
					'title' => 'Dpto.',
					'validation' => 'string|max:50',
				],
				'consultant_city' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Ciudad',
					'validation' => 'string|max:50',
				],
				'consultant_district' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Barrio',
					'validation' => 'string|max:50',
				],
				'consultant_postal_code' => [
					'css_class' => 'col-1-12',
					'type' => 'inputText',
					'title' => 'C. postal',
					'validation' => 'string|max:10',
				],
			],
		],
		'Personas significativas' => [
			'Persona significativa 1' => [
				'significant_firstname_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'significant_lastname_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'significant_cellphone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'significant_phone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
				'significant_link_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Vínculo',
					'validation' => 'string|max:50',
				],
			],
			'Persona significativa 2' => [
				'significant_firstname_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'significant_lastname_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'significant_cellphone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'significant_phone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
				'significant_link_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Vínculo',
					'validation' => 'string|max:50',
				],
			],
			'Persona significativa 3' => [
				'significant_firstname_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'significant_lastname_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'significant_cellphone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'significant_phone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
				'significant_link_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Vínculo',
					'validation' => 'string|max:50',
				],
			],
		],
		'Datos del derivante' => [
			'' => [
				'derivative_firstname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'derivative_lastname' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'derivative_cellphone' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'derivative_phone' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
		],
		'Datos de profesionales' => [
			'Profesional o Institución 1' => [
				'professional_name_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'professional_cellphone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'professional_phone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
			'Profesional o Institución 2' => [
				'professional_name_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'professional_cellphone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'professional_phone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
			'Profesional o Institución 3' => [
				'professional_name_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'professional_cellphone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'professional_phone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
			'Médico 1' => [
				'doctor_firstname_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'doctor_lastname_1' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'doctor_cellphone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'doctor_phone_1' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
			'Médico 2' => [
				'doctor_firstname_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'doctor_lastname_2' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'doctor_cellphone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'doctor_phone_2' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
			'Médico 3' => [
				'doctor_firstname_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Nombre',
					'validation' => 'string|max:50',
				],
				'doctor_lastname_3' => [
					'css_class' => 'col-1-4',
					'type' => 'inputText',
					'title' => 'Apellido',
					'validation' => 'string|max:50',
				],
				'doctor_cellphone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Celular',
					'validation' => 'string|max:20',
				],
				'doctor_phone_3' => [
					'css_class' => 'col-1-6',
					'type' => 'inputText',
					'title' => 'Teléfono',
					'validation' => 'string|max:20',
				],
			],
		],
	];

	private $patientAdmitionData = [
		'Motivo de la consulta' => [
			'' => [
				'background_reason' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => '',
				],
			]
		],
		'Antecedentes' => [
			'Del problema o enfermedad' => [
				'background_problem_evolution' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Evolución del problema',
					'validation' => '',
				],
				'background_solution_strategy' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Estrategia de solución',
					'validation' => '',
				],
				'background_previous_treatments' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Tratamientos anteriores',
					'validation' => '',
				],
			],
			'Personales' => [
				'background_evolutive_history' => [
					'css_class' => 'col-1-2',
					'type' => 'textarea',
					'title' => 'Historia evolutiva y acontecimientos vitales significativos',
					'validation' => '',
				],
				'background_previous_psi_treatments' => [
					'css_class' => 'col-1-2',
					'type' => 'textarea',
					'title' => 'Tratamientos anteriores psi en la vida del paciente',
					'validation' => '',
				],
			],
			'Familiares o Genograma' => [
				'background_family_genogram' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => '',
				],
			],
			'Médicos Personales' => [
				'background_medical_personal' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => '',
				],
			],
			'Médicos Familiares' => [
				'background_medical_family' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => '',
				],
			],
		],
		'Síntesis de la entrevista' => [
			'' => [
				'interview_synthesis_themes' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Temas',
					'validation' => '',
				],
				'interview_synthesis_appetite' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Datos significativos sobre apetito, sexualidad, sueño y tiempo libre',
					'validation' => '',
				],
				'interview_synthesis_work_study_data' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Datos sobre trabajo y/o estudio',
					'validation' => '',
				],
			],
		],
		'Examen mental' => [
			'' => [
				'mental_exam_presentation' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Presentación',
					'validation' => '',
				],
				'mental_exam_attention' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Atención',
					'validation' => '',
				],
				'mental_exam_orientation' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Orientación (tiempo y espacio)',
					'validation' => '',
				],
				'mental_exam_memory' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Memoria',
					'validation' => '',
				],
				'mental_exam_thought' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Contenidos del pensamiento',
					'validation' => '',
				],
				'mental_exam_thought_perception' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Procesos y estructura del pensamiento y percepciones',
					'validation' => '',
				],
				'mental_exam_mood' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Estado anímico',
					'validation' => '',
				],
				'mental_exam_engine' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Motor',
					'validation' => '',
				],
			],
		],
		'Otros comentarios' => [
			'' => [
				'other_comments' => [
					'css_class' => '',
					'type' => 'textarea',
					'validation' => 'string|'],
			],
		],
		'Diagnóstico presuntivo por DSM' => [
			'' => [
				'dsm_diagnostic' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Diagnóstico',
					'validation' => '',
				],
				'dsm_medical_disease' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Enfermedad médica',
					'validation' => '',
				],
				'dsm_psychosocial_stress' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Estrés psicosocial',
					'validation' => '',
				],
				'dsm_operating_level' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Nivel de funcionamiento',
					'validation' => '',
				],
			],
		],
		'Programa propuesto de tratamiento' => [
			'' => [
				'proposed_treatment_program' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => '',
				],
			],
		],
		'Indicaciones y resolución' => [
			'' => [
				'indications_evaluation_request' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Solicitud de evaluación',
					'validation' => '',
				],
				'indications_medical_evaluation_request' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Solicitud de evaluación médica',
					'validation' => '',
				],
				'indications_laboratory' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Laboratorio',
					'validation' => '',
				],
				'indications_others' => [
					'css_class' => 'col-1-4',
					'type' => 'textarea',
					'title' => 'Otros',
					'validation' => '',
				],
			],
		],
		'Red familiar y social' => [
			'' => [
				'family_social_red' => [
					'css_class' => 'col-1-5',
					'type' => 'select',
					'title' => 'Red familiar y social',
					'options' => [
						['id' => 'amplia', 'value' => 'Amplia'],
						['id' => 'media', 'value' => 'Media'],
						['id' => 'escasa', 'value' => 'Escasa'],
					],
					'validation' => 'in:amplia,media,escasa',
				],
				'family_social_red_expectations' => [
					'css_class' => 'col-1-5',
					'type' => 'select',
					'title' => 'Expectativas',
					'options' => [
						['id' => 'amplia', 'value' => 'Amplia'],
						['id' => 'media', 'value' => 'Media'],
						['id' => 'baja', 'value' => 'Baja'],
					],
					'validation' => 'in:amplia,media,baja',
				],
				'family_social_red_motivation' => [
					'css_class' => 'col-1-5',
					'type' => 'select',
					'title' => 'Motivación',
					'options' => [
						['id' => 'amplia', 'value' => 'Amplia'],
						['id' => 'media', 'value' => 'Media'],
						['id' => 'baja', 'value' => 'Baja'],
					],
					'validation' => 'in:amplia,media,baja',
				],
				'family_social_red_rectance' => [
					'css_class' => 'col-1-5',
					'type' => 'select',
					'title' => 'Rectancia',
					'options' => [
						['id' => 'amplia', 'value' => 'Amplia'],
						['id' => 'media', 'value' => 'Media'],
						['id' => 'baja', 'value' => 'Baja'],
					],
					'validation' => 'in:amplia,media,baja',
				],
				'family_social_red_coping_style' => [
					'css_class' => 'col-1-5',
					'type' => 'select',
					'title' => 'Estilo de afrontamiento',
					'options' => [
						['id' => 'internalizador', 'value' => 'Internalizador'],
						['id' => 'externalizador', 'value' => 'Externalizador'],
						['id' => 'oscilante', 'value' => 'Oscilante'],
					],
					'validation' => 'in:internalizador,externalizador,oscilante',
				],
			],
		],
		'Datos' => [
			'table' => [
				'data_n_trat_prev_psic' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° trat prev psic',
					'validation' => 'integer',
				],
				'data_n_trat_prev_psiq' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° trat prev psiq',
					'validation' => 'integer',
				],
				'data_n_ts' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° TS',
					'validation' => 'integer',
				],
				'data_idea_s_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'Idea S UM (0-5)',
					'validation' => 'integer',
				],
				'data_n_interm_clin' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° Intern Clin',
					'validation' => 'integer',
				],
				'data_n_int_psic' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° Int Psic',
					'validation' => 'integer',
				],
				'data_n_d_int_psic' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° D Int Psic',
					'validation' => 'integer',
				],
				'data_n_d_emer_psiq' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° D Emer Psiq',
					'validation' => 'integer',
				],
				'data_n_ingesta_atrac_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° Ingesta/atrac UM',
					'validation' => 'integer',
				],
				'data_n_autoles_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° Autoles UM',
					'validation' => 'integer',
				],
				'data_n_e_vcia_verbal_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° E Vcia Verbal UM',
					'validation' => 'integer',
				],
				'data_n_e_vcia_fis_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° E Vcia Fis UM',
					'validation' => 'integer',
				],
				'data_n_d_c_cons_ol_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° D c/cons OL UM',
					'validation' => 'integer',
				],
				'data_n_c_cons_drogas_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° c/cons Drogas UM',
					'validation' => 'integer',
				],
				'data_n_d_c_cons_med_c_um' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° D c/cons Med C UM',
					'validation' => 'integer',
				],
				'data_n_mentiras' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'Mentiras',
					'validation' => 'integer',
				],
				'data_robos_intra_fliar' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'Robos Intra Fliar',
					'validation' => 'integer',
				],
				'data_robos_extra_fliar' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'Robos Extra Fliar',
					'validation' => 'integer',
				],
				'data_n_d_aus_clases_trab' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'N° D aus clases/trab',
					'validation' => 'integer',
				],
				'data_edad_com_1_trat' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'Edad Com 1° trat',
					'validation' => 'integer',
				],
				'data_c_autoreporte' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'C/Autoreporte',
					'validation' => 'integer',
				],
				'data_s_autoreporte' => [
					'css_class' => 'col-1-5',
					'type' => 'inputText',
					'title' => 'S/Autoreporte',
					'validation' => 'integer',
				],
			],
		],
	];

	private $patientHCData = [
		'Consulta' => [
			'' => [
				'type' => [
					'css_class' => '',
					'type' => 'inputRadio',
					'title' => 'Tipo de consulta',
					'options' => [
						['id' => 'E.I.', 'value' => 'Entrevista Individual pacientes'],
						['id' => 'G.H.', 'value' => 'Grupo de entrenamiento en habilidades'],
						['id' => 'G.H.F.A.', 'value' => 'Grupo de entrenamiento en habilidades a familiares y allegados'],
						['id' => 'E.F.A.', 'value' => 'Entrevista familiar y allegados'],
						['id' => 'E.P.', 'value' => 'Entrevista Psiquiátrica'],
						['id' => 'I.C.', 'value' => 'Interconsulta'],
						['id' => 'otros', 'value' => 'Otros', 'with_text' => 'type_info'],
					],
					'validation' => 'in:E.I.,G.H.,G.H.F.A.,E.F.A.,E.P.,I.C.,otros|required',
				],
			]
		],
		'Evolución' => [
			'' => [
				'detail' => [
					'css_class' => '',
					'type' => 'textarea',
					'title' => '',
					'validation' => 'required',
				],
				'files' => [
					'css_class' => 'col-1-3',
					'type' => 'dropzone',
					'title' => 'Archivos',
				],
			]
		],
	];

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$data['filters'] = [
			'id' => [
				'type' => 'where',	
				'value' => $request->input('id'),
			],
			'patient_document_number' => [
				'type' => 'where',	
				'value' => $request->input('patient_document_number'),
			],
			'patient_firstname' => [
				'type' => 'where',	
				'value' => $request->input('patient_firstname'),
			],
			'patient_lastname' => [
				'type' => 'where',	
				'value' => $request->input('patient_lastname'),
			],
			'patient_phone' => [
				'type' => 'where',	
				'value' => $request->input('patient_phone'),
			],
			'patient_email_1' => [
				'type' => 'where',	
				'value' => $request->input('patient_email'),
			],
			'patient_email_2' => [
				'type' => 'where',	
				'value' => $request->input('patient_email'),
			],
			'patient_email_3' => [
				'type' => 'where',	
				'value' => $request->input('patient_email'),
			],
		];

		if (in_array(Auth::user()->permissions, ['professional'])) {

			$data['professional'] = Professional::where('user_id', Auth::user()->id)->first();

			$data['patients'] = Patient::whereDoesntHave('professionals', function ($query) use ($data) {
				$query->where('id', $data['professional']->id);
			})->orderBy('patient_firstname', 'ASC');

			$filters = false;

			foreach ($data['filters'] as $itemName => $filter) {
				if ( ! empty($filter['value'])) {
					$data['patients'] = $data['patients']->{$filter['type']}($itemName, 'like', '%'.$filter['value'].'%');
					$filters = true;
				}
			}

			if ($filters) {
				$data['back_url'] = route('patients.index');
			}

			$data['patientsHighlight'] = Patient::whereHas('professionals', function ($query) use ($data) {
				$query->where('id', $data['professional']->id);
			})->orderBy('patient_firstname', 'ASC');

			foreach ($data['filters'] as $itemName => $filter) {
				if ( ! empty($filter['value'])) {
					$data['patientsHighlight'] = $data['patientsHighlight']->{$filter['type']}($itemName, 'like', '%'.$filter['value'].'%');
				}
			}

			$data['patientsHighlight'] = $data['patientsHighlight']->get();

			$data['patients'] = $data['patients']->paginate(20);

		} else {

			$data['patients'] = Patient::orderBy('patient_firstname', 'ASC');

			$filters = false;

			foreach ($data['filters'] as $itemName => $filter) {
				if ( ! empty($filter['value'])) {
					$data['patients'] = $data['patients']->{$filter['type']}($itemName, 'like', '%'.$filter['value'].'%');
					$filters = true;
				}
			}

			if ($filters) {
				$data['back_url'] = route('patients.index');
			}

			$data['patients'] = $data['patients']->paginate(20);
		}

		return view('patients', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.index');
		}

		$data = [
			'items' => $this->patientData,
			'back_url' => route('patients.index'),
			'form_url' => route('patients.store'),
			'form_method' => 'POST',
			'title' => 'Crear nuevo paciente',
		];

		return view('form', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.index');
		}

		$validation = [];

		foreach ($this->patientData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);

		$patient = new Patient;

		foreach ($this->patientData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					$patient->$itemName = $request->$itemName;
				}
			}
		}

		$patient->save();

		$request->session()->flash('success', 'El paciente fue creado con éxito.');

		return redirect()->route('patients.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
		$patient = Patient::findOrFail($id);

		$data = [
			'items' => $this->patientData,
			'back_url' => route('patients.index'),
			'form_url' => route('patients.update', ['id' => $id]),
			'form_method' => 'PUT',
			'title' => 'Paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
			'only_view' => true,
		];

		foreach ($data['items'] as $key => &$itemGroup) {
			foreach ($itemGroup as $key => &$itemSubroup) {
				foreach ($itemSubroup as $itemName => &$item) {
					$item['value'] = $patient->$itemName;
				}
			}
		}

		return view('form', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		$patient = Patient::findOrFail($id);

		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.show', ['patient_id' => $patient->id]);
		}

		$data = [
			'items' => $this->patientData,
			'back_url' => route('patients.index'),
			'form_url' => route('patients.update', ['id' => $id]),
			'form_method' => 'PUT',
			'title' => 'Paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
		];

		foreach ($data['items'] as $key => &$itemGroup) {
			foreach ($itemGroup as $key => &$itemSubroup) {
				foreach ($itemSubroup as $itemName => &$item) {
					$item['value'] = $patient->$itemName;
				}
			}
		}

		return view('form', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$patient = Patient::findOrFail($id);
		
		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional', 'admisor'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.show', ['patient_id' => $patient->id]);
		}

		$validation = [];

		foreach ($this->patientData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);

		foreach ($this->patientData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					$patient->$itemName = $request->$itemName;
				}
			}
		}

		$patient->save();

		$request->session()->flash('success', 'Se editaron con éxito los datos.');

		return redirect()->route('patients.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function report(Request $request, $patient_id = false)
	{
		if ( ! in_array(Auth::user()->permissions, ['administrator', 'superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.index');
		}

		$validation = [
			'since' => 'required|date',
			'to' => 'required|date',
		];

		$this->validate($request, $validation);

		$data = [
			'back_url' => route('patients.index'),
			'patient_id' => ($request->patient_id) ? $request->patient_id : false,
			'since' => (strtotime($request->since) < strtotime($request->to)) ? $request->since : $request->to,
			'to' => (strtotime($request->since) < strtotime($request->to)) ? $request->to : $request->since,
			'consultationTypes' => [
				['id' => 'E.I.', 'value' => 'Entrevista Individual pacientes'],
				['id' => 'G.H.', 'value' => 'Grupo de entrenamiento en habilidades'],
				['id' => 'G.H.F.A.', 'value' => 'Grupo de entrenamiento en habilidades a familiares y allegados'],
				['id' => 'E.F.A.', 'value' => 'Entrevista familiar y allegados'],
				['id' => 'E.P.', 'value' => 'Entrevista Psiquiátrica'],
				['id' => 'I.C.', 'value' => 'Interconsulta'],
				['id' => 'otros', 'value' => 'Otros', 'with_text' => 'type_info'],
			],
		];

		$data['pdf_url'] = route('patients.report', ['patient_id' => $patient_id]) . '?pdf=true&since='.$data['since'].'&to='.$data['to'];

		$data['patients'] = Patient::whereHas('hcDates', function ($query) use ($data) {
			$query->where('hc_dates.created_at', '>=', $data['since'].' 00:00:00');
			$query->where('hc_dates.created_at', '<=', $data['to'].' 23:59:59');
			if (in_array(Auth::user()->permissions, ['professional'])) {
				$query->join('professionals', 'professionals.id', '=', 'hc_dates.professional_id')
					->where('professionals.user_id', '=', Auth::user()->id);
			}
			if (in_array(Auth::user()->permissions, ['administrator'])) {
				$query->where('type', '!=', 'otros');
			}
		})->orderBy('patient_firstname', 'ASC');

		if ($data['patient_id']) {
			$data['patients'] = $data['patients']->where('id', $data['patient_id']);
		}

		$data['patients'] = $data['patients']->get();

		if ($request->pdf) {
			$view =  \View::make('pdf.patientsReport', $data)->render();
			$pdf = \App::make('dompdf.wrapper');
			$pdf->loadHTML($view);
			$pdf->output();
			return $pdf->stream('reporte_pacientes_'.$data['since'].'_'.$data['to'].'.`pdf');
		}

		return view('patientsReport', $data);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index_admissions(Request $request, $patient_id)
	{
		$data['patient'] = Patient::findOrFail($patient_id);

		$data['admissions'] = $data['patient']->admissions()->orderBy('created_at', 'DESC')->paginate(20);

		return view('admissions', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create_admissions(Request $request, $patient_id)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.admissions.show', ['patient_id' => $patient_id]);
		}

		$patient = Patient::findOrFail($patient_id);

		$data = [
			'items' => $this->patientAdmitionData,
			'back_url' => route('patients.admissions.index', ['patient_id' => $patient_id]),
			'form_url' => route('patients.admissions.store', ['patient_id' => $patient_id]),
			'form_method' => 'POST',
			'title' => 'Crear nueva admisión del paciente ' . $patient->patient_firstname . ' ' . $patient->patient_lastname,
		];

		return view('form', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store_admissions(Request $request, $patient_id)
	{
		if ( ! in_array(Auth::user()->permissions, ['superadmin', 'professional'])) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.admissions.show', ['patient_id' => $patient_id]);
		}

		$patient = Patient::findOrFail($patient_id);
		
		$professional = Professional::where('user_id', Auth::user()->id)->first();

		$patientAdmision = new PatientAdmision;

		$patientAdmision->professional_id = $professional->id;

		$validation = [];

		foreach ($this->patientAdmitionData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);

		$patientAdmision->patient_id = $patient_id;

		foreach ($this->patientAdmitionData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					$patientAdmision->$itemName = $request->$itemName;
				}
			}
		}

		$patientAdmision->save();

		$request->session()->flash('success', 'Se editaron con éxito los datos de admisión del paciente.');

		return redirect()->route('patients.admissions.index', ['patient_id' => $patient_id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show_admissions(Request $request, $patient_id, $admision_id)
	{
		$patient = Patient::findOrFail($patient_id);

		$patientAdmision = PatientAdmision::where('id', $admision_id)->where('patient_id', $patient_id)->first();

		$data = [
			'items' => $this->patientAdmitionData,
			'back_url' => route('patients.admissions.index', ['patient_id' => $patient_id]),
			'form_method' => 'PUT',
			'title' => 'Admisión del paciente "' . $patient['patient_firstname'] . ' ' . $patient['patient_lastname'] . '"',
			'only_view' => true,
		];

		foreach ($data['items'] as $key => &$itemGroup) {
			foreach ($itemGroup as $key => &$itemSubroup) {
				foreach ($itemSubroup as $itemName => &$item) {
					$item['value'] = $patientAdmision->$itemName;
				}
			}
		}

		return view('form', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function index_hc(Request $request, $patient_id)
	{
		$data['patient'] = Patient::findOrFail($patient_id);
		$data['hc_dates'] = HCDate::where('patient_id', $patient_id)->orderBy('created_at', 'DESC');

		if ($request->pdf) {
			$data['hc_dates'] = $data['hc_dates']->get();

			$data['hc_types'] = [
				'E.I.' => 'Entrevista Individual pacientes',
				'G.H.' => 'Grupo de entrenamiento en habilidades',
				'G.H.F.A.' => 'Grupo de entrenamiento en habilidades a familiares y allegados',
				'E.F.A.' => 'Entrevista familiar y allegados',
				'E.P.' => 'Entrevista Psiquiátrica',
				'I.C.' => 'Interconsulta',
				'otros' => 'Otros',
			];

			$view =  \View::make('pdf.hc', $data)->render();

			$pdf = \App::make('dompdf.wrapper');

			$pdf->loadHTML($view);
			$pdf->output();

			return $pdf->stream('hc_'.$data['patient']->patient_firstname.'_'.$data['patient']->patient_lastname.'_'.date('d-m-Y').'.pdf');

		} else {
			$data['hc_dates'] = $data['hc_dates']->paginate(20);

			return view('hc', $data);
		}

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create_hc(Request $request, $patient_id)
	{
		if ( ! (in_array(Auth::user()->permissions, ['professional']))) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.hc', ['patient_id' => $patient_id]);
		}

		$patient = Patient::findOrFail($patient_id);

		$data = [
			'items' => $this->patientHCData,
			'back_url' => route('patients.hc', ['patient_id' => $patient_id]),
			'form_url' => route('patients.hc.store', ['patient_id' => $patient_id]),
			'form_method' => 'POST',
			'title' => 'Crear nueva entrada en la historia clínica de "'.$patient->patient_firstname.' '.$patient->patient_lastname.'"',
		];

		return view('form', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store_hc(Request $request, $patient_id)
	{
		if ( ! (in_array(Auth::user()->permissions, ['professional']))) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.hc', ['patient_id' => $patient_id]);
		}

		$validation = [];

		foreach ($this->patientHCData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ( ! empty($item['validation'])) {
						$validation[$itemName] = $item['validation'];
					}
				}
			}
		}

		$this->validate($request, $validation);

		$patient = Patient::findOrFail($patient_id);
		$professional = Professional::where('user_id', Auth::user()->id)->first();

		$hc_date = new HCDate;

		$hc_date->patient_id = $patient_id;
		$hc_date->professional_id = $professional->id;

		foreach ($this->patientHCData as $key => $itemGroup) {
			foreach ($itemGroup as $key => $itemSubroup) {
				foreach ($itemSubroup as $itemName => $item) {
					if ($item['type'] == 'inputFile') {
						if ($request->file($itemName)) {
							$file = $request->file($itemName);
							$name = time().'-'.$file->getClientOriginalName();
							$path = "hc/$name";
							Storage::put($path, File::get($file->getRealPath()));
							$hc_date->$itemName = $path;
						}
					} else if ($item['type'] == 'dropzone') {
						$files = $request->{'dropzone_'.$itemName};
						if ($files) {
							$first = true;
							foreach ($files as $fileName) {
								if ( ! $first) {
									$hc_date->$itemName .= ',';
								}
								$first = false;
								$hc_date->$itemName .= $fileName;
							}
						}
					} else {
						$hc_date->$itemName = $request->$itemName;
						if ($item['type'] == 'inputRadio') {
							foreach ($item['options'] as $key => &$option) {
								if (isset($option['with_text']) and $option['with_text']) {
									$hc_date->{$option['with_text']} = $request->{$option['with_text']};
								}
							}
						}
					}
				}
			}
		}

		$hc_date->save();

		$request->session()->flash('success', 'Se creó correctamente la consulta en la HC.');

		return redirect()->route('patients.hc', ['patient_id' => $patient_id]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function assignProfessional(Request $request, $patient_id)
	{
		if ( ! (in_array(Auth::user()->permissions, ['professional']))) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.hc', ['patient_id' => $patient_id]);
		}

		$patient = Patient::findOrFail($patient_id);
		$professional = Professional::where('user_id', Auth::user()->id)->first();
		$patient->professionals()->attach($professional->id);

		$request->session()->flash('success', 'Se asignó correctamente el paciente.');

		return redirect()->route('patients.index', ['patient_id' => $patient_id]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function unAssignProfessional(Request $request, $patient_id)
	{
		if ( ! (in_array(Auth::user()->permissions, ['professional']))) {
			$request->session()->flash('error', 'No tenés permisos para realizar esta acción.');

			return redirect()->route('patients.hc', ['patient_id' => $patient_id]);
		}

		$patient = Patient::findOrFail($patient_id);
		$professional = Professional::where('user_id', Auth::user()->id)->first();

		$patient->professionals()->detach($professional->id);

		$request->session()->flash('success', 'Se desasignó correctamente el paciente.');

		return redirect()->route('patients.index', ['patient_id' => $patient_id]);
	}
}
