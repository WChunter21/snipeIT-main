<?php

return array(
    'ad'				        => 'Active Directory',
    'ad_domain'				    => 'Active Directory 도메인',
    'ad_domain_help'			=> '이것은 보통은 당신의 이메일 주소와 같지만, 항상 같지는 않습니다.',
    'is_ad'				        => '이것은 Active Directory 서버입니다.',
	'alert_email'				=> '알림 전송',
	'alerts_enabled'			=> '알림 사용',
	'alert_interval'			=> '(일자) 최대값 만료 알림',
	'alert_inv_threshold'		=> '재고 알림 최대값',
	'asset_ids'					=> '자산 ID',
	'auto_increment_assets'		=> '자동-증가 자산 ID 생성',
	'auto_increment_prefix'		=> '접두사 (부가적)',
	'auto_incrementing_help'    => '이 것을 설정하려면 자동-증가 자산 ID 사용을 하세요.',
	'backups'					=> '예비품',
	'barcode_settings'			=> '바코드 설정',
    'confirm_purge'			    => '삭제 확인',
    'confirm_purge_help'		=> '당신의 삭제된 기록들을 지우려면 다음 상자 안에 "DELETE" 를 입력하세요. 이 동작은 취소할 수 없습니다.',
	'custom_css'				=> '사용자 지정 CSS',
	'custom_css_help'			=> '사용하려는 사용자 CSS를 입력하세요. &lt;style&gt;&lt;/style&gt; 태그들은 넣지 마세요.',
	'default_currency'  		=> '기본 통화',
	'default_eula_text'			=> '기본 사용권 계약',
  'default_language'					=> '기본 언어',
	'default_eula_help_text'	=> '또한 특정 자산 분류에는 맞춤형 사용권 계약들과 연결을 해야 합니다.',
    'display_asset_name'        => '자산 명 표시',
    'display_checkout_date'     => '반출 일자 표시',
    'display_eol'               => '테이블 보기에서 폐기일 표시',
    'display_qr'                => 'Qr 코드 표시',
	'display_alt_barcode'		=> '1 D 바코드 표시',
	'barcode_type'				=> '2D 바코드 형식',
	'alt_barcode_type'			=> '1D 바코드 형식',
    'alt_barcode_reader_tooltip'=> 'Barcode on the label can be read by Barcode Reader.<br/>Barcode reader need to be set with Prefix(Default: ~) and Suffix(Default: Enter).<br/>If you want change barcode prefix or suffix you can do it in config/barcode.php',
    'eula_settings'				=> '최종 사용 계약 설정',
    'eula_markdown'				=> '이 최종 사용 계약은 <a href="https://help.github.com/articles/github-flavored-markdown/">GFM을 따른다</a>.',
    'general_settings'			=> '일반 설정',
	'generate_backup'			=> '백업 생성',
    'header_color'              => '머릿말 색상',
    'info'                      => '이 설정들은 설치본의 특정 분야를 설정하는 것입니다.',
    'laravel'                   => 'Laravel 버전',
    'ldap_enabled'              => 'LDAP 활성',
    'ldap_integration'          => 'LDAP 연동',
    'ldap_settings'             => 'LDAP 설정',
    'ldap_server'               => 'LDAP 서버',
    'ldap_server_help'          => '이것은 (복호화또는 TLS 용) ldap로 시작합니다:// 또는 (SSL용) ldaps://',
	'ldap_server_cert'			=> 'LDAP SSL 인증서 확인',
	'ldap_server_cert_ignore'	=> '유효하지 않은 SSL 인증서 허용',
	'ldap_server_cert_help'		=> '자체 서명한 SSL 인증서를 사용하고, 유효하지 않은 SSL 인증서 사용을 승인하고자 하는 경우, 이 확인 상자를 선택하십시오.',
    'ldap_tls'                  => 'TLS 사용',
    'ldap_tls_help'             => '이것은 LDAP 서버에서 STARTTLS를 실행하는 경우에만 검사됩니다. ',
    'ldap_uname'                => 'LDAP 연결용 사용자명',
    'ldap_pword'                => 'LDAP 연결용 비밀번호',
    'ldap_basedn'               => 'Base BIND DN',
    'ldap_filter'               => 'LDAP 필터',
    'ldap_pw_sync'              => 'LDAP 암호 동기화',
    'ldap_pw_sync_help'         => '로컬 암호와 PDAP 암호를 동기화 하지 않으려면 이 박스의 체크를 풀어주세요. 이것을 해제하면 당신의 LDAP 서버에 여러 원인으로 접속할 수 없다면 사용자들도 로그인 할 수 없게 됩니다.',
    'ldap_username_field'       => '사용자명 항목',
    'ldap_lname_field'          => '성:',
    'ldap_fname_field'          => 'LDAP 이름',
    'ldap_auth_filter_query'    => 'LDAP 인증 요청',
    'ldap_version'              => 'LDAP 버전',
    'ldap_active_flag'          => 'LDAP 활성 플래그',
    'ldap_emp_num'              => 'LDAP 피고용인 번호',
    'ldap_email'                => 'LDAP 이메일',
    'load_remote_text'          => '원격 스크립트',
    'load_remote_help_text'		=> '이 Snipe-IT 설치는 인터넷에서 스크립트들을 읽어 올 수 있습니다.',
    'logo'                    	=> '로고',
    'full_multiple_companies_support_help_text' => '자신이 속한 공급자 자산에 공급자를 할당하는 것을 제한하는 사용자들 (관리자 포함)',
    'full_multiple_companies_support_text' => '전체 다중 공급자들 지원',
    'optional'					=> '선택 사항',
    'per_page'                  => '페이지 당 결과',
    'php'                       => 'PHP 버전',
    'php_gd_info'               => 'QR 코드를 보려면 php-gd를 설치하셔야 합니다. 설치 설명서를 참고하세요.',
    'php_gd_warning'            => 'PHP 이미지 처리 및 GD 플러그인이 설치되어 있지 않습니다.',
    'qr_help'                   => '이 것을 설정하려면 먼저 QR 코드 사용하기를 하세요',
    'qr_text'                   => 'QR 코드 문구',
    'setting'                   => '설정',
    'settings'                  => '설정',
    'site_name'                 => '사이트 명',
    'slack_botname'             => 'Slack Bot 명',
    'slack_channel'             => 'Slack 채널',
    'slack_endpoint'            => 'Slack 종료점',
    'slack_integration'         => 'Slack 설정',
    'slack_integration_help'    => '느슨한 통합은 부가기능이지만, 사용하고 싶다면 종료점과 채널이 필요합니다. 느슨한 통합을 설정하려면, 우선  Slack 장부에<a href=":slack_link" target="_new">webhook 저장소를 생성해야 합니다</a>.',
    'snipe_version'  			=> 'Snipe-IT 버전',
    'system'                    => '시스템 정보',
    'update'                    => '갱신 설정',
    'value'                     => '가치',
    'brand'                     => '브랜딩',
    'about_settings_title'      => '설정에 관해',
    'about_settings_text'       => '이 설정들은 설치본의 특정 분야를 설정하는 것입니다.',
    'labels_per_page'           => '페이지 당 꼬리표',
    'label_dimensions'          => '꼬리표 크기(인치)',
    'next_auto_tag_base'        => 'Next auto-increment',
    'page_padding'             => '페이지 여백 (인치)',
    'purge'                    => '삭제된 기록들 지우기',
    'labels_display_bgutter'    => '꼬리표 아래쪽 가운데 여백',
    'labels_display_sgutter'    => '꼬리표 옆쪽 가운데 여백',
    'labels_fontsize'           => '꼬리표 글꼴 크기',
    'labels_pagewidth'          => '꼬리표 지 넓이',
    'labels_pageheight'         => '꼬리표 지 높이',
    'label_gutters'        => '꼬리표 여백(인치)',
    'page_dimensions'        => '꼬리표 면적(인치)',
    'label_fields'          => '꼬리표 가시 항목',
    'inches'        => '인치',
    'width_w'        => '넓이',
    'height_h'        => '높이',
    'text_pt'        => 'pt',
    'two_factor'        => '2단계 인증',
    'two_factor_secret'        => '2단계 코드',
    'two_factor_enrollment'        => '2단계 등록',
    'two_factor_enabled_text'        => '2중 활성화',
    'two_factor_reset'        => '2중 보안 재설정',
    'two_factor_reset_help'        => '이 기능은 강제로 사용자들을 구글 인증을 사용하여 각각의 장치에 다시 등록하게 합니다. 이 기능은 현재 등록한 장치들이 분실이나 도난 당했다면 유용할 것입니다. ',
    'two_factor_reset_success'          => '2중 장치 재설정이 되었습니다',
    'two_factor_reset_error'          => '2중 장치 재설정이 실패했습니다',
    'two_factor_enabled_warning'        => '2중 활성화가 현재 활성화되지 않다면 구글 인증으로 등록된 장치를 즉시 강제로 인증하게 할 것입니다. 당신은 하나가 현재 등록되지 않았다면 당신의 장치를 등록할 수 있어야 합니다.',
    'two_factor_enabled_help'        => '이 것은 구글 인증을 사용하는 2중 인증 기능을 동작시킵니다.',
    'two_factor_optional'        => '선택적 (허용 하는 경우 사용자가 활성화 하거나 비활성화할 수 있습니다)',
    'two_factor_required'        => '모든 사용자에게 필수',
    'two_factor_disabled'        => '비활성화',
    'two_factor_enter_code'	=> '2중 코드 입력',
    'two_factor_config_complete'	=> '코드 제출',
    'two_factor_enabled_edit_not_allowed' => '관리자가 이 설정의 수정을 허용하지 않았습니다.',
    'two_factor_enrollment_text'	=> "2중 인증은 필수입니다만, 당신의 장치는 아직 등록되지 않았습니다. 구글 인증 앱을 실행하고 등록할 장치 아래의 QR 코드를 스캔하세요. 당신의 장치가 등록됐다면, 아래 코드를 입력하세요",
    'require_accept_signature'      => '서명 필수',
    'require_accept_signature_help_text'      => '이 기능을 활성화하면 자산 수락시에 물리적 서명을 필수로 하게 됩니다.',
    'left'        => '왼쪽',
    'right'        => '오른쪽',
    'top'        => '위',
    'bottom'        => '아래',
    'vertical'        => '수직',
    'horizontal'        => '수평',
    'zerofill_count'        => '0 채움을 포함한, 자산 태그 길이',
);
