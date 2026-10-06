<?php
/**
 * 求人情報（/recruit/）
 *
 * 応募フォームは Contact Form 7「求人用フォーム」（SUMIYOSHI_CF7_RECRUIT）を表示します。
 * 勤続年数は固定ページの ACF フィールド（入社日）から自動計算します。
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Recruit', get_the_title() );

$sumiyoshi_interviews = array(
	array(
		'name'   => 'Sさん',
		'photo'  => '2025/03/77cb9ac5732aa3e98a4fedf6af70fac5.jpg',
		'joined' => '昭和45年4月1日入社',
		'tenure' => sumiyoshi_tenure_label( get_queried_object_id(), 'shinozuka_hire_date', '19700401' ),
		'qa'     => array(
			'入社のきっかけ'          => "実家が百姓を営んでいて、跡継ぎが兄だったため別で仕事を探していました。\n手に職をつけたいと思い、親戚が電気工事会社で働いているのをきっかけに私もその道を目指すようになりました。\n通っていた職業訓練校にこの会社の当時の社長が就職の説明会にきたのをきっかけに入社することを決意しました。",
			'やりがい'             => "仕事自体楽しいのですがやはり仕事頑張って遊びに行く！仲間と飲みに行く！を楽しみに日々仕事に励んでいました。\n当時は川崎市以外でも仕事がありいろんな場所で働けるのも楽しかったです。",
			'仕事を通じての思い出'       => '電気工事は他の業種の方たちとの繋がりもあるので、いろんな人と出会い、そしてよく飲みにいきました。',
			'入社を考えている方々にメッセージ' => "仕事は面白いです！電気工事を選んで良かったと今でも思います。電気は世の中から無くならないものですし変わっていくものでもあります。\n蛍光灯～LED～有機ELと時代の変化と共に次々と生まれる新しい技術にワクワクします。\n電気自動車なんてものは私が生まれた頃は、想像すらできなかったです。\n仕事は奥が深く、最初はわからないことも多々あると思いますが、なるべく分かりやすく教えていくので、一緒に楽しく働きましょう！",
		),
	),
	array(
		'name'   => 'Nさん',
		'photo'  => '2025/05/751a61a62b286f4c0b5a7d85bf13a7a3.png',
		'joined' => '令和6年2月1日入社',
		'tenure' => sumiyoshi_tenure_label( get_queried_object_id(), 'nakamura_hire_date', '20240201' ),
		'qa'     => array(
			'入社動機とこの会社に決めた理由'   => '前職で電気工事の職人として働いていたのですが、施工管理にチャレンジしたいと思い応募しました。面接の際、社長からいろいろな話を伺いながら社内を案内していただき、雰囲気や福利厚生の良さを感じる事で入社を決意しました。',
			'入社後の感想'           => "入社前に感じた雰囲気の良さは入社後も変わっていません。コミュニケーションの取りやすい環境だからこそ結束力があり、それが会社の強みとして仕事上でも生きていると感じています。\n教育面においても、未経験の分野で不安はありましたが、個人の状況や能力を把握したうえで指導していただけるので、不安はすぐに払拭されました。自分自身の成長を感じながら目標に向かっていく充実感はこの会社だからこそ味わえるものだと感じています。",
			'目標'               => '今は上司や先輩方に色々と教えていただきながら現場作業や管理業務をおこなっていますが、一日でも早く仕事を覚えてこの会社に貢献したいです。',
			'入社を考えている方々にメッセージ' => "建設業界はものづくりに対する達成感や、形に残る仕事に対するやりがいを感じることができる業種です。工事が終わった際に、問題なく設備が作動したり取り付けた照明のライトが一斉に点灯したりした時は安堵感や達成感を強く感じます。人々の暮らしに必要不可欠な役割を果たしている貢献度の高い業種ですのでやりがいを感じながら働けます！\n未経験でも学べる環境が整っています。上司や先輩の方々も親身に相談に乗ってくださるので、安心して働くことができます。一緒に働ける日を楽しみにしています！",
		),
	),
);
?>

<section class="section">
	<div class="container">
		<div class="section__head fade-in">
			<h2 class="section__title">【賞与年2回】施工管理者募集！現場作業員募集！未経験者も多数活躍中！寮あり！土日祝日休み！現場は川崎市内のみ！残業少なめ！</h2>
			<div class="tag-list recruit-tags">
				<span class="tag tag--blue">正社員</span>
				<span class="tag tag--blue">未経験OK</span>
				<span class="tag tag--blue">週休2日制</span>
				<span class="tag tag--green">年末年始、GW、夏季休暇あり</span>
				<span class="tag tag--green">未経験者大歓迎</span>
				<span class="tag tag--orange">バイク通勤OK</span>
				<span class="tag tag--orange">自動車通勤OK</span>
			</div>
		</div>
		<?php get_template_part( 'template-parts/stat-cards' ); ?>
	</div>
</section>

<!-- 先輩社員の声 -->
<section class="section section--gray">
	<div class="container">
		<div class="section__head section__head--center fade-in">
			<span class="section__label">Interview</span>
			<h2 class="section__title">先輩社員の声</h2>
		</div>
		<div class="interviews">
			<?php foreach ( $sumiyoshi_interviews as $sumiyoshi_person ) : ?>
				<article class="interview fade-in">
					<div class="interview__head">
						<img class="interview__photo" src="<?php echo esc_url( sumiyoshi_upload_url( $sumiyoshi_person['photo'] ) ); ?>" alt="<?php echo esc_attr( $sumiyoshi_person['name'] ); ?>" loading="lazy">
						<div>
							<p class="interview__name"><?php echo esc_html( $sumiyoshi_person['name'] ); ?></p>
							<p class="interview__meta"><?php echo esc_html( $sumiyoshi_person['joined'] ); ?><br>勤続 <strong><?php echo esc_html( $sumiyoshi_person['tenure'] ); ?></strong></p>
						</div>
					</div>
					<?php foreach ( $sumiyoshi_person['qa'] as $sumiyoshi_q => $sumiyoshi_a ) : ?>
						<details class="accordion">
							<summary><?php echo esc_html( $sumiyoshi_q ); ?></summary>
							<div class="accordion__body"><?php echo nl2br( esc_html( $sumiyoshi_a ) ); ?></div>
						</details>
					<?php endforeach; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<div class="apply-actions">
			<a class="btn-primary" href="#apply-form">✉ 応募フォームから応募する</a>
			<a class="apply-tel" href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><small>電話での応募は</small><span class="num">📞 <?php echo esc_html( SUMIYOSHI_TEL ); ?></span></a>
		</div>
	</div>
</section>

<!-- 募集情報 -->
<section class="section">
	<div class="container">
		<div class="section__head fade-in">
			<span class="section__label">Detail</span>
			<h2 class="section__title">募集情報</h2>
		</div>
		<table class="recruit-table fade-in">
			<tbody>
				<tr><th>PR</th><td>【賞与年2回】施工管理者募集！現場作業員募集！未経験者も多数活躍中！寮あり！土日祝日休み！現場は川崎市内のみ！残業少なめ！</td></tr>
				<tr><th>職種</th><td><span class="tag tag--blue">正社員</span><br>施工管理、現場作業員</td></tr>
				<tr><th>給与</th><td>22万円～<br>交通費：全額支給<br>① 施工管理経験者<br>35万円～<br>② 施工管理未経験者<br>25万円～<br>③ 現場作業員経験者<br>25万円～<br>④ 現場作業員未経験者<br>22万円～<br><br>■賞与：年2回<br>【各種手当あり】<br>資格手当<br>通勤手当<br>家族手当<br>休日出勤手当<br>【試用期間】<br>3ヶ月</td></tr>
				<tr><th>職業内容</th><td>【施工管理】<br>■見積作成<br>予算計画を立て、コストを計算して見積を作成します。<br>■CAD図面作成<br>図面をCADソフトで作成・修正し電気工事の詳細なレイアウトを決定します。<br>■打合せ<br>現場で作業を担当する作業員や発注者との打ち合わせ。<br>■現場監督<br>現場に出向き、施工が計画通りに進んでいるかを監督・管理します。<br><br>【現場作業員】<br>■公共施設の新築・改修工事。電気工事全般をお任せします。エリアは川崎市内のみ。未経験でも安心！イチから丁寧におしえます◎経験者大歓迎！</td></tr>
				<tr><th>勤務時間</th><td>8：00～17：00</td></tr>
				<tr><th>休日/休暇</th><td>土日祝日<br>夏季休暇<br>年末年始</td></tr>
				<tr><th>経験/資格</th><td><span class="tag tag--green">未経験OK</span> <span class="tag tag--green">独立支援制度あり</span><br>第二種電気工事士、2級電気工事施工管理士があれば即戦力です！<br>資格がなくても資格取得支援が充実しています。</td></tr>
				<tr><th>待遇/福利厚生</th><td><span class="tag tag--orange">バイク通勤OK</span> <span class="tag tag--orange">希望者寮完備</span> <span class="tag tag--orange">有給休暇(年10日)</span><br>■社会保険完備<br>■賞与あり：年2回<br>■昇給あり：年１回<br>■交通費全額支給<br>■資格取得支援あり</td></tr>
				<tr><th>勤務地・面接地</th><td>神奈川県川崎市高津区明津14番地1（住吉電機株式会社 本社ビル）</td></tr>
			</tbody>
		</table>
		<?php if ( sumiyoshi_map_src() ) : ?>
			<iframe class="map-embed recruit-map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
				src="<?php echo esc_url( sumiyoshi_map_src() ); ?>"
				title="勤務地・面接地 地図"></iframe>
		<?php endif; ?>
	</div>
</section>

<!-- 応募フォーム -->
<section class="section section--gray" id="apply-form">
	<div class="container">
		<div class="section__head section__head--center fade-in">
			<span class="section__label">Entry</span>
			<h2 class="section__title">住吉電機株式会社の求人募集に応募する</h2>
		</div>
		<div class="contact-form">
			<?php sumiyoshi_contact_form( SUMIYOSHI_CF7_RECRUIT ); ?>
			<div class="apply-actions apply-actions--form">
				<a class="apply-tel" href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><small>電話での応募は</small><span class="num">📞 <?php echo esc_html( SUMIYOSHI_TEL ); ?></span></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
