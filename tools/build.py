#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
住吉電機株式会社 静的サイトジェネレータ

WordPressエクスポート(2026-06-10)から抽出した実コンテンツをもとに、
共通ヘッダー/フッターを持つ全ページのHTMLを生成する。
WPテーマ化の際は layout() がそのまま header.php / footer.php に対応する。

使い方:  python3 tools/build.py   （リポジトリルートで実行）

画像について:
  現サイト(wp-content/uploads)の画像URLを直接参照している。
  日本国内からの閲覧であれば表示される（サーバーが国外IPを遮断しているため、
  海外からは表示されない）。WPテーマ化時にメディアライブラリの画像をそのまま使う。
"""
import os, html

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
UP = "https://www.sumiyoshidennki.com/wp-content/uploads"

LOGO = f"{UP}/2025/03/logo251106.svg"

# ----------------------------------------------------------
# データ：News & Topics（WPエクスポートの news 投稿 全14件）
# ----------------------------------------------------------
NEWS = [
    dict(date="2025-05-27", slug="aed", title="AED設置",
         thumb=f"{UP}/2025/05/75936_0.jpg",
         body=["弊社入口付近にAEDを設置しました。", "どなたでも使用できる状態になっておりますので、緊急時はご活用ください。"]),
    dict(date="2024-10-09", slug="solar-v2h", title="太陽光発電設備V2H導入",
         thumb=f"{UP}/2025/05/6A14AF7D-ADA7-472B-8EA4-EF82AC724DD6-scaled.jpg",
         body=["太陽光発電設備とV2Hシステムを導入しました。", "導入済みのEV車と組合せることで災害時に電源の供給を可能にします。", "災害時はスマートホンやモバイルバッテリー充電の提供を予定しています。"]),
    dict(date="2024-02-14", slug="ev-car", title="EV車を導入しました",
         thumb=f"{UP}/2025/05/Image-1-768x576-1.jpg",
         body=["営業車としてeKクロスEVを導入しました"]),
    dict(date="2024-02-14", slug="kawasaki-cityhall-solar-completed", title="川崎市新本庁舎太陽光発電設備設置工事が完成しました",
         thumb=f"{UP}/2025/05/20230608_060811683_iOS-768x576-1.jpg",
         body=["令和5年6月完成"]),
    dict(date="2023-04-05", slug="nagasawa-lighting-completed", title="長沢浄水場ほか２箇所　照明設備等改良工事が完成しました。",
         thumb=None,
         body=["長沢浄水場ほか２箇所　照明設備等改良工事が完成しました。"]),
    dict(date="2022-03-15", slug="kawasaki-cityhall-solar-logo", title="川崎市新本庁舎太陽光発電設備設置工事　ロゴの掲示",
         thumb=f"{UP}/2025/05/image0-768x1024-1.jpeg",
         body=["外周足場サインシートに弊社のロゴが掲示されました。"]),
    dict(date="2022-03-03", slug="tama-fire-station-completed", title="多摩消防署栗谷出張所改築電気その他設備工事　完成",
         thumb=f"{UP}/2025/05/2ad40a9f634b9cf21bee3d9116eb9da7.jpg",
         body=["多摩消防署栗谷出張所改築電気その他設備工事が完成しました。"]),
    dict(date="2022-03-03", slug="nagasawa-lighting-order", title="長沢浄水場ほか２箇所　照明設備等改良工事　受注",
         thumb=None,
         body=["長沢浄水場ほか２箇所　照明設備等改良工事を受注致しました。"]),
    dict(date="2021-10-11", slug="contact-form", title="問い合わせについて",
         thumb=None,
         body=["問い合わせフォームを実装しました。"]),
    dict(date="2021-10-11", slug="kawasaki-cityhall-solar-order", title="川崎市新本庁舎太陽光発電設備設置工事　受注",
         thumb=None,
         body=["川崎市新本庁舎太陽光発電設備設置工事を受注致しました。"]),
    dict(date="2021-08-16", slug="sdgs-partner", title="かわさきSDGsパートナー",
         thumb=f"{UP}/2025/05/IMG_1177-768x1024-1.jpg",
         body=["かわさきSDGsパートナーに登録されました。"]),
    dict(date="2021-08-10", slug="hp-updating", title="ホームページ変更中",
         thumb=None,
         body=["見えにくい部分の修正と機能追加を行っています。"]),
    dict(date="2021-05-20", slug="current-construction", title="現在施工中の工事",
         thumb=None,
         body=["多摩消防署栗谷出張所改築電気その他設備工事"]),
    dict(date="2021-05-20", slug="hp-renewal", title="ホームページ変更",
         thumb=None,
         body=["サーバー変更に伴い、ホームページを変更しました。"]),
]

# ----------------------------------------------------------
# データ：施工実績（WPエクスポートの jisseki 投稿 全13件）
# 並び順は現サイトのアーカイブ表示に準拠
# ----------------------------------------------------------
JISSEKI = [
    dict(slug="road-lighting-4", title="道路照明設置その４", period="令和6年3月",
         thumb=f"{UP}/2025/05/25319-rotated-e1748761477667.jpg", gallery=[]),
    dict(slug="hosoyama-pump-ups", title="細山送水ポンプ所　監視制御設備用ＵＰＳ更新工事", period="令和6年3月",
         thumb=f"{UP}/2025/03/18509fd22173d4e5c04bacb18eae425e.jpg", gallery=[]),
    dict(slug="haisuihonkan-ups", title="１号配水本管配水流量計用ＵＰＳ等修理工事", period="令和6年3月",
         thumb=f"{UP}/2025/05/82e3f4fe4b3f761242349cf14d646747.jpg", gallery=[]),
    dict(slug="nagasawa-lighting", title="長沢浄水場ほか２箇所　照明設備等改良工事", period="令和5年3月",
         thumb=f"{UP}/2025/03/1461470b87d3ff0d39b368ad713b24b1.jpg", gallery=[]),
    dict(slug="higashiogishima-gaito", title="東扇島・千鳥町外灯設備補修工事", period="令和4年3月",
         thumb=f"{UP}/2025/03/46a6995dee50f503497122cb107b6455.jpg", gallery=[]),
    dict(slug="kawasaki-cityhall-solar", title="川崎市新本庁舎太陽光発電設備設置工事", period="令和5年6月",
         thumb=f"{UP}/2025/05/20230608_060811683_iOS-768x576-1.jpg", gallery=[]),
    dict(slug="chitose-maeda-housing", title="千年前田住宅５号棟緊急通報システム改修工事", period="平成31年3月",
         thumb=f"{UP}/2025/03/2aafd2b071a3a0cb9f5393fe7dc6087f.jpg", gallery=[]),
    dict(slug="nakahara-jhs", title="中原中学校受変電その他設備改修工事", period="令和1年10月",
         thumb=f"{UP}/2025/03/e770f280f712c1336f30925bbd1e596c.jpg", gallery=[]),
    dict(slug="tama-fire-station", title="多摩消防署栗谷出張所改築電気その他設備工事", period="令和4年2月",
         thumb=f"{UP}/2025/03/3e63fbe51e6ec7b830628410e417e975-scaled.jpg",
         gallery=[f"{UP}/2025/03/f028b6429e77b97f2dfe9134e9537f28.jpg",
                  f"{UP}/2025/03/b6e433da796c8d5f4ed0793fd8cd10d3.jpg",
                  f"{UP}/2025/03/83473221d3d1ca0c11379702d458cc39.jpg",
                  f"{UP}/2025/03/ba3b20088403b7d2c1ba2d9707e0d3a9.jpg"]),
    dict(slug="kowan-tennis-court", title="港湾振興会館テニスコート照明改修その２工事", period="令和2年12月",
         thumb=f"{UP}/2025/03/2740044ad843b3747614686a2d22524a.jpg", gallery=[]),
    dict(slug="arima-es-battery", title="有馬小学校蓄電池設備設置工事", period="令和2年3月",
         thumb=f"{UP}/2025/03/5c2c143af75e66828fd06e7fad5724d6-scaled.png", gallery=[]),
    dict(slug="suenaga-housing", title="末長住宅新築第2号電気設備工事", period="平成29年10月",
         thumb=f"{UP}/2025/03/6986fc96f46e718ad8050015c4ff0cce.jpg",
         gallery=[f"{UP}/2025/03/a645a66addc940a32dbdf136bb278188.jpg",
                  f"{UP}/2025/03/3cab4afeda0107d4f2c7628b3d1edb1d.jpg"]),
    dict(slug="asao-shiminkan", title="麻生市民館・図書館高圧真空切替開閉器長寿命化整備業務委託", period="令和3年12月",
         thumb=f"{UP}/2025/03/1aa4d0d2824b2921bd99e67981c7c88d.jpg",
         gallery=[f"{UP}/2025/03/1aa4d0d2824b2921bd99e67981c7c88d.jpg",
                  f"{UP}/2025/03/6f1cb757b8a16d6a0fd5486fd775bf1c.jpg"]),
]

# 求人ステータスカード（TOP・求人ページ共通／現サイトと同値）
STATS = [
    ("年間休日日数", f"{UP}/2025/06/Group-3750.svg", "120～125", "日"),
    ("平均有給取得日数", f"{UP}/2025/06/Frame-1.svg", "10", "日"),
    ("創業年", f"{UP}/2025/06/Group-3747.svg", "1962", "年"),
    ("平均残業時間", f"{UP}/2025/06/Frame-2.svg", "0.5", "時間"),
    ("平均勤続年数", f"{UP}/2025/06/Group-3748.svg", "20", "年"),
    ("賞与支給日", f"{UP}/2025/06/Group-3746.svg", "2", "回"),
]

# 会社案内 4項目（現サイトと同じ画像・リンク）
INFO_CARDS = [
    ("代表挨拶", "Greeting", "/information/greeting/", f"{UP}/2025/05/image-9.png"),
    ("会社概要", "Company", "/information/company/", f"{UP}/2025/06/Frame-8.png"),
    ("沿革", "History", "/information/history/", f"{UP}/2025/06/56b7b0de4be422688604b363ad508afd-3.png"),
    ("アクセス", "Access", "/information/access/", f"{UP}/2025/05/image-8.png"),
]

ESC = html.escape

# ----------------------------------------------------------
# 共通レイアウト（WP化時: header.php / footer.php に対応）
# ----------------------------------------------------------
def layout(*, title, description, body, active="", is_top=False):
    page_title = "住吉電機株式会社 – SUMIYOSHI ELECTRIC CO.,LTD" if is_top else f"{title} – 住吉電機株式会社"

    def cur(key):
        return ' aria-current="page"' if key == active else ""

    return f"""<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{ESC(page_title)}</title>
<meta name="description" content="{ESC(description)}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<!-- ▼ ここから header.php 相当 -->
<div class="announce-bar">
  <a href="/recruit/">一緒に働ける仲間を住吉電機株式会社では募集しております。</a>
</div>
<header class="site-header">
  <div class="site-header__inner">
    <a href="/" class="site-logo">
      <img src="{LOGO}" alt="住吉電機株式会社">
    </a>
    <nav class="global-nav" id="global-nav">
      <ul class="global-nav__list">
        <li><a href="/"{cur("top")}>トップページ</a></li>
        <li><a href="/news/"{cur("news")}>News &amp; Topics</a></li>
        <li class="has-dropdown">
          <a href="/information/"{cur("information")}>会社案内</a>
          <ul class="dropdown">
            <li><a href="/information/greeting/">代表挨拶</a></li>
            <li><a href="/information/company/">会社概要</a></li>
            <li><a href="/information/history/">沿　革</a></li>
            <li><a href="/information/access/">アクセス</a></li>
          </ul>
        </li>
        <li><a href="/jisseki/"{cur("jisseki")}>施工実績</a></li>
        <li><a href="/recruit/"{cur("recruit")}>求人情報</a></li>
        <li><a href="/contact/"{cur("contact")}>お問い合わせ</a></li>
      </ul>
      <a class="btn-recruit" href="/recruit/">求人情報</a>
    </nav>
    <div class="header-contact">
      <p class="header-tel">
        <span class="header-tel__number">044-755-6161（代）</span>
        <span class="header-tel__note">お電話でのお問い合わせ</span>
      </p>
      <a class="btn-recruit" href="/recruit/">求人情報</a>
      <button class="nav-toggle" aria-expanded="false" aria-controls="global-nav" aria-label="メニューを開く">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
<!-- ▲ ここまで header.php 相当 -->

<main>
{body}
</main>

<!-- ▼ ここから footer.php 相当 -->
<footer class="site-footer">
  <div class="container site-footer__inner">
    <div class="footer-company">
      <div class="footer-company__logo">
        <img src="{LOGO}" alt="" width="40" height="40">
        <span class="footer-company__name">住吉電機株式会社</span>
      </div>
      <address class="footer-company__address">
        神奈川県川崎市高津区明津14番地1<br>
        044-755-6161（代）
      </address>
    </div>
    <nav class="footer-nav">
      <ul>
        <li><a href="/">トップページ</a></li>
        <li><a href="/news/">News &amp; Topics</a></li>
        <li><a href="/jisseki/">施工実績</a></li>
        <li><a href="/recruit/">求人情報</a></li>
      </ul>
      <ul>
        <li><a href="/information/">会社案内</a></li>
        <li><a href="/information/greeting/">代表挨拶</a></li>
        <li><a href="/information/company/">会社概要</a></li>
        <li><a href="/information/history/">沿革</a></li>
        <li><a href="/information/access/">アクセス</a></li>
        <li><a href="/contact/">お問い合わせ</a></li>
      </ul>
    </nav>
  </div>
  <div class="copyright">
    <div class="container">
      <span>Copyright © SUMIYOSHI ELECTRIC CO.,LTD All Rights Reserved.</span>
      <span><a href="#">プライバシーポリシー</a></span><!-- TODO: 現サイトのプライバシーポリシーのリンク先確認 -->
      <span>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</span>
    </div>
  </div>
</footer>
<button class="to-top" aria-label="ページ上部へ戻る">↑</button>
<!-- ▲ ここまで footer.php 相当 -->

<script src="/assets/js/main.js"></script>
</body>
</html>
"""

def page_header(label, title):
    return f"""  <div class="page-header">
    <div class="container">
      <p class="page-header__label">{ESC(label)}</p>
      <h1 class="page-header__title">{title}</h1>
    </div>
  </div>"""

def breadcrumb(*trail):
    lis = ['<li><a href="/">ホーム</a></li>']
    for t in trail[:-1]:
        lis.append(f'<li><a href="{t[1]}">{ESC(t[0])}</a></li>')
    lis.append(f"<li>{ESC(trail[-1][0])}</li>")
    items = "\n        ".join(lis)
    return f"""  <nav class="breadcrumb" aria-label="パンくずリスト">
    <div class="container">
      <ol>
        {items}
      </ol>
    </div>
  </nav>"""

def news_list_html(posts):
    out = []
    for p in posts:
        out.append(f"""        <li class="news-list__item">
          <a href="/news/{p['slug']}.html" class="news-list__link">
            <time class="news-list__date" datetime="{p['date']}">{p['date'].replace('-', '/')}</time>
            <span class="news-list__title">{ESC(p['title'])}</span>
          </a>
        </li>""")
    return "\n".join(out)

def stat_cards_html():
    out = []
    for label, icon, value, unit in STATS:
        out.append(f"""        <div class="stat-card fade-in">
          <span class="stat-card__label">{ESC(label)}</span>
          <img class="stat-card__icon" src="{icon}" alt="" loading="lazy">
          <p class="stat-card__value">{ESC(value)}<small>{ESC(unit)}</small></p>
        </div>""")
    return "\n".join(out)

def work_cards_html(works, limit=None):
    out = []
    for w in works[:limit] if limit else works:
        out.append(f"""        <a class="work-card fade-in" href="/jisseki/{w['slug']}.html">
          <img class="work-card__img" src="{w['thumb']}" alt="{ESC(w['title'])}" loading="lazy">
          <div class="work-card__body">
            <h3 class="work-card__title">{ESC(w['title'])}</h3>
            <p class="work-card__date">{ESC(w['period'])}</p>
          </div>
        </a>""")
    return "\n".join(out)

def info_cards_html():
    out = []
    for title, _en, url, img in INFO_CARDS:
        out.append(f"""        <a class="info-card fade-in" href="{url}">
          <img class="info-card__img" src="{img}" alt="{ESC(title)}" loading="lazy">
          <div class="info-card__body">
            <span class="info-card__title">{ESC(title)}</span>
            <span class="info-card__arrow">→</span>
          </div>
        </a>""")
    return "\n".join(out)

def info_index_html():
    """TOPページ用：番号付きインデックスリスト"""
    out = []
    for i, (title, en, url, img) in enumerate(INFO_CARDS, 1):
        out.append(f"""          <li class="fade-in">
            <a class="info-index__link" href="{url}">
              <span class="info-index__num">{i:02d}</span>
              <img class="info-index__thumb" src="{img}" alt="" loading="lazy">
              <span class="info-index__title">{ESC(title)}<small>{ESC(en)}</small></span>
              <span class="info-index__arrow">→</span>
            </a>
          </li>""")
    return "\n".join(out)

W = {}  # path -> html

# ----------------------------------------------------------
# トップページ
# ----------------------------------------------------------
hero_slides = [f"{UP}/2025/03/Group-3744.png", f"{UP}/2025/03/Group-3764-1.png"]
slides_html = "\n".join(
    f'    <div class="hero__slide" style="background-image:url(\'{u}\')"></div>' for u in hero_slides
)
top_body = f"""  <!-- メインビジュアル（現サイトのキャッチコピーを縦書きで継承） -->
  <section class="hero">
{slides_html}
    <p class="hero__catch">
      <span>川崎市で電気工事を</span>
      <span>営んで半世紀以上。</span>
      <span>技術力と信頼で</span>
      <span>その間に培った</span>
      <span>お客様に安心をお届けします。</span>
    </p>
    <div class="hero__dots" role="tablist" aria-label="スライド切り替え"></div>
  </section>

  <!-- News & Topics -->
  <section class="section">
    <div class="container">
      <div class="section__head fade-in">
        <span class="section__label">News &amp; Topics</span>
        <h2 class="section__title">お知らせ</h2>
      </div>
      <ul class="news-list fade-in">
{news_list_html(NEWS[:6])}
      </ul>
      <div class="section__more fade-in">
        <a href="/news/" class="btn-more">お知らせ一覧はコチラ＞</a>
      </div>
    </div>
  </section>

  <!-- 会社案内 -->
  <section class="section section--gray">
    <div class="container">
      <div class="info-index">
        <div class="info-index__head fade-in">
          <span class="section__label">Information</span>
          <h2 class="section__title">会社案内</h2>
          <p class="info-index__lead">昭和28年の創業以来、川崎の地で電気工事を営んでまいりました。住吉電機株式会社についてご紹介します。</p>
        </div>
        <ul class="info-index__list">
{info_index_html()}
        </ul>
      </div>
    </div>
  </section>

  <!-- 施工実績 -->
  <section class="section">
    <div class="container">
      <div class="section__head section__head--center fade-in">
        <span class="section__label">Works</span>
        <h2 class="section__title">施工実績</h2>
      </div>
      <div class="works-grid works-grid--top">
{work_cards_html(JISSEKI, limit=4)}
      </div>
      <div class="section__more fade-in">
        <a href="/jisseki/" class="btn-more">施工実績はこちら＞</a>
      </div>
    </div>
  </section>

  <!-- 求人情報 -->
  <section class="section section--cream">
    <div class="container">
      <div class="section__head section__head--center fade-in">
        <span class="section__label">Recruit</span>
        <h2 class="section__title">求人情報</h2>
      </div>
      <div class="recruit-copy fade-in">
        <p class="recruit-copy__main">正社員募集中！<br>川崎市で半世紀以上つづく電気工事業で一緒に働きませんか？</p>
        <p class="recruit-copy__sub">未経験から手に職を。寮完備＆土日祝休みで働きやすい環境！</p>
        <p class="recruit-copy__text">
          川崎市内中心の現場で転勤なし。施工管理者、現場作業員の募集をしています。<br>
          しっかりとしたサポート体制で、未経験者も多数活躍中！<br>
          寮完備で遠方からの応募も歓迎。残業少なめ・土日祝休みで、プライベートも充実。<br>
          「安定して働きたい」「手に職をつけたい」「長く働きたい」そんな方に最適な職場です。
        </p>
      </div>
      <div class="stat-cards">
{stat_cards_html()}
      </div>
      <div class="section__more fade-in">
        <a href="/recruit/" class="btn-more">求人情報はこちら＞</a>
      </div>
    </div>
  </section>"""
W["index.html"] = layout(
    title="", is_top=True, active="top",
    description="住吉電機株式会社は、川崎市で電気工事を営んで半世紀以上。技術力と信頼でその間に培ったお客様に安心をお届けします。電気配線工事・計装工事・消防施設工事・電気通信工事。",
    body=top_body)

# ----------------------------------------------------------
# News & Topics 一覧 / 詳細
# ----------------------------------------------------------
news_archive_body = f"""{page_header("News & Topics", "News &amp; Topics")}
{breadcrumb(("News & Topics",))}

  <section class="section">
    <div class="container">
      <ul class="news-list fade-in">
{news_list_html(NEWS)}
      </ul>
    </div>
  </section>"""
W["news/index.html"] = layout(
    title="News & Topics", active="news",
    description="住吉電機株式会社からのお知らせ一覧です。",
    body=news_archive_body)

for p in NEWS:
    thumb_html = f'\n        <img class="article__thumb" src="{p["thumb"]}" alt="{ESC(p["title"])}" loading="lazy">' if p["thumb"] else ""
    paragraphs = "\n          ".join(f"<p>{ESC(line)}</p>" for line in p["body"])
    body = f"""{page_header("News & Topics", ESC(p["title"]))}
{breadcrumb(("News & Topics", "/news/"), (p["title"],))}

  <section class="section">
    <div class="container">
      <article class="article">
        <div class="article__meta">
          <time class="article__date" datetime="{p['date']}">{p['date'].replace('-', '/')}</time>
        </div>{thumb_html}
        <div class="article__body">
          {paragraphs}
        </div>
        <p class="article__back"><a href="/news/" class="btn-more">お知らせ一覧へ戻る</a></p>
      </article>
    </div>
  </section>"""
    W[f"news/{p['slug']}.html"] = layout(
        title=p["title"], active="news",
        description=p["body"][0][:100],
        body=body)

# ----------------------------------------------------------
# 施工実績 一覧 / 詳細
# ----------------------------------------------------------
jisseki_archive_body = f"""{page_header("Works", "施工実績")}
{breadcrumb(("施工実績",))}

  <section class="section">
    <div class="container">
      <div class="works-grid">
{work_cards_html(JISSEKI)}
      </div>
    </div>
  </section>"""
W["jisseki/index.html"] = layout(
    title="施工実績", active="jisseki",
    description="住吉電機株式会社の施工実績。川崎市新本庁舎太陽光発電設備設置工事をはじめ、公共施設を中心とした電気設備工事の実績をご紹介します。",
    body=jisseki_archive_body)

for w_ in JISSEKI:
    gallery = ""
    if w_["gallery"]:
        imgs = "\n".join(f'          <img src="{u}" alt="{ESC(w_["title"])} 施工写真" loading="lazy">' for u in w_["gallery"])
        gallery = f"""
        <div class="work-gallery">
{imgs}
        </div>"""
    body = f"""{page_header("Works", ESC(w_["title"]))}
{breadcrumb(("施工実績", "/jisseki/"), (w_["title"],))}

  <section class="section">
    <div class="container">
      <article class="article">
        <div class="article__meta">
          <span class="article__date">{ESC(w_["period"])}</span>
        </div>
        <img class="article__thumb" src="{w_['thumb']}" alt="{ESC(w_['title'])}" loading="lazy">{gallery}
        <p class="article__back"><a href="/jisseki/" class="btn-more">施工実績一覧へ戻る</a></p>
      </article>
    </div>
  </section>"""
    W[f"jisseki/{w_['slug']}.html"] = layout(
        title=w_["title"], active="jisseki",
        description=f"{w_['title']}（{w_['period']}）の施工実績です。",
        body=body)

# ----------------------------------------------------------
# 会社案内（インデックス）
# ----------------------------------------------------------
info_body = f"""{page_header("Information", "会社案内")}
{breadcrumb(("会社案内",))}

  <section class="section">
    <div class="container">
      <div class="info-cards">
{info_cards_html()}
      </div>
    </div>
  </section>"""
W["information/index.html"] = layout(
    title="会社案内", active="information",
    description="住吉電機株式会社の会社案内。代表挨拶・会社概要・沿革・アクセスをご覧いただけます。",
    body=info_body)

# ----------------------------------------------------------
# 代表挨拶
# ----------------------------------------------------------
greeting_body = f"""{page_header("Greeting", "代表挨拶")}
{breadcrumb(("会社案内", "/information/"), ("代表挨拶",))}

  <section class="section">
    <div class="container">
      <div class="greeting-layout">
        <img src="{UP}/2025/06/top.png" alt="代表取締役 高橋賢次" loading="lazy">
        <div>
          <h2 class="greeting__title">ごあいさつ</h2>
          <div class="greeting__body">
            <p>1953年の創業から70年以上を数え、昭和、平成、令和と3つの時代で様々な電気工事を行ってまいりました。最近、電気工事ってどんなことをするのかと良く聞かれます。弊社の電気工事は身近なコンセントやスイッチ、照明器具から太陽光発電設備や施設内の変電所まで幅広く行っております。今後も時代の流れに合わせた電気工事をご提供してまいります。</p>
            <p>これからの時代の目標として従業員を始め弊社に関わる方々の幸せ、地域の皆様への貢献、電気工事業を含めた建設業の発展を掲げ、邁進していく所存です。どうかお付合い頂けると幸いです。</p>
          </div>
          <p class="greeting__sign">住吉電機株式会社<br>代表取締役 高橋　賢次</p>
        </div>
      </div>
    </div>
  </section>"""
W["information/greeting/index.html"] = layout(
    title="代表挨拶", active="information",
    description="住吉電機株式会社 代表取締役 高橋賢次よりごあいさつ。1953年の創業から70年以上、時代の流れに合わせた電気工事をご提供してまいります。",
    body=greeting_body)

# ----------------------------------------------------------
# 会社概要
# ----------------------------------------------------------
company_body = f"""{page_header("Company", "会社概要")}
{breadcrumb(("会社案内", "/information/"), ("会社概要",))}

  <section class="section">
    <div class="container">
      <div class="company-layout">
        <img src="{UP}/2025/06/Frame-8.png" alt="住吉電機株式会社 本社ビル" loading="lazy">
        <table class="info-table">
          <tbody>
            <tr><th>名　称</th><td>住吉電機株式会社</td></tr>
            <tr><th>代表取締役</th><td>高橋　賢次</td></tr>
            <tr><th>本社所在地</th><td>神奈川県川崎市高津区明津14番地1</td></tr>
            <tr><th>電話</th><td>044-755-6161（代）</td></tr>
            <tr><th>資本金</th><td>51,500,000 円</td></tr>
            <tr><th>許可番号</th><td>神奈川県知事（般）　1396号</td></tr>
            <tr><th>創立</th><td>昭和28年11月1日</td></tr>
            <tr><th>営業品目</th><td>（1）電気配線工事・計装工事<br>　　 消防施設工事・電気通信工事<br>（2）貸ビル業</td></tr>
            <tr><th>従業員数</th><td>10名（2025年3月現在）</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>"""
W["information/company/index.html"] = layout(
    title="会社概要", active="information",
    description="住吉電機株式会社の会社概要。本社所在地：神奈川県川崎市高津区明津14番地1、創立：昭和28年11月1日。電気配線工事・計装工事・消防施設工事・電気通信工事。",
    body=company_body)

# ----------------------------------------------------------
# 沿革（現サイトの掲載内容を年代順に整理）
# ----------------------------------------------------------
HISTORY = [
    ("昭和28年 11月", "東京電力（株）を退社し、高橋徳太郎 個人にて川崎市中原区に置いて営業を開始。"),
    ("昭和32年 4月", "神奈川県庁及び川崎市役所指名業者に登録される。"),
    ("昭和38年 7月", "資本金 2,200,000で住吉電機株式会社設立。<br>有限会社住吉電機商会休業。"),
    ("昭和39年 6月", "東京電力（株）6KV昇圧工事業者に登録される。"),
    ("昭和41年 4月", "日本住宅公団、東京都庁指名業者に登録される。"),
    ("昭和44年 8月", "増資 1,200,000により資本金10,000,000となる。"),
    ("昭和47年 4月", "住吉ビル完成。"),
    ("昭和47年 6月", "増資 5,000,000により資本金 15,000,000となる。"),
    ("昭和51年 4月", "現住所に本社ビルを設立して営業開始。"),
    ("昭和62年 6月", "高橋 隆 代表取締役社長に就任。"),
    ("平成 2年 4月", "増資 10,500,000により資本金 31,500,000となる。"),
    ("平成27年 4月", "増資 20,000,000により資本金 51,500,000となる。"),
    ("令和元年 8月", "高橋 賢次 代表取締役社長に就任。現在に至る。"),
]
timeline_html = "\n".join(f"""        <div class="timeline__item fade-in">
          <p class="timeline__year">{ESC(y)}</p>
          <p class="timeline__text">{t}</p>
        </div>""" for y, t in HISTORY)
history_body = f"""{page_header("History", "沿革")}
{breadcrumb(("会社案内", "/information/"), ("沿革",))}

  <section class="section">
    <div class="container">
      <div class="timeline">
{timeline_html}
      </div>
    </div>
  </section>"""
W["information/history/index.html"] = layout(
    title="沿革", active="information",
    description="住吉電機株式会社の沿革。昭和28年の創業から現在までの歩みをご紹介します。",
    body=history_body)

# ----------------------------------------------------------
# アクセス（現サイトと同じストリートビュー埋め込み＋地図）
# ----------------------------------------------------------
access_body = f"""{page_header("Access", "アクセス")}
{breadcrumb(("会社案内", "/information/"), ("アクセス",))}

  <section class="section">
    <div class="container">
      <div class="article">
        <table class="info-table" style="margin-bottom:40px">
          <tbody>
            <tr><th>所在地</th><td>神奈川県川崎市高津区明津14番地1</td></tr>
            <tr><th>電話</th><td>044-755-6161（代）</td></tr>
          </tbody>
        </table>
        <iframe class="map-embed" style="margin-bottom:32px"
          src="https://www.google.com/maps/embed?pb=!4v1743146114250!6m8!1m7!1sKf5jSiNYm6P3JBHiGM0gDw!2m2!1d35.56946006058778!2d139.6358572797338!3f35.39!4f52.41999999999999!5f1.009049021386966"
          allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
          title="住吉電機株式会社 ストリートビュー"></iframe>
        <iframe class="map-embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
          src="https://maps.google.com/maps?q=%E7%A5%9E%E5%A5%88%E5%B7%9D%E7%9C%8C%E5%B7%9D%E5%B4%8E%E5%B8%82%E9%AB%98%E6%B4%A5%E5%8C%BA%E6%98%8E%E6%B4%A514-1&output=embed"
          title="住吉電機株式会社 所在地"></iframe>
      </div>
    </div>
  </section>"""
W["information/access/index.html"] = layout(
    title="アクセス", active="information",
    description="住吉電機株式会社へのアクセス。神奈川県川崎市高津区明津14番地1。",
    body=access_body)

# ----------------------------------------------------------
# 求人情報
# ----------------------------------------------------------
def acc(title_, body_):
    return f"""            <details class="accordion">
              <summary>{ESC(title_)}</summary>
              <div class="accordion__body">{body_}</div>
            </details>"""

recruit_body = f"""{page_header("Recruit", "求人情報")}
{breadcrumb(("求人情報",))}

  <section class="section">
    <div class="container">
      <div class="section__head fade-in">
        <h2 class="section__title">【賞与年2回】施工管理者募集！現場作業員募集！未経験者も多数活躍中！寮あり！土日祝日休み！現場は川崎市内のみ！残業少なめ！</h2>
        <div class="tag-list" style="margin-top:20px">
          <span class="tag tag--blue">正社員</span>
          <span class="tag tag--blue">未経験OK</span>
          <span class="tag tag--blue">週休2日制</span>
          <span class="tag tag--green">年末年始、GW、夏季休暇あり</span>
          <span class="tag tag--green">未経験者大歓迎</span>
          <span class="tag tag--orange">バイク通勤OK</span>
          <span class="tag tag--orange">自動車通勤OK</span>
        </div>
      </div>
      <div class="stat-cards">
{stat_cards_html()}
      </div>
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
        <article class="interview fade-in">
          <div class="interview__head">
            <img class="interview__photo" src="{UP}/2025/03/77cb9ac5732aa3e98a4fedf6af70fac5.jpg" alt="Sさん" loading="lazy">
            <div>
              <p class="interview__name">Sさん</p>
              <p class="interview__meta">昭和45年4月1日入社<br>勤続 <strong data-hire-date="1970-04-01">--年</strong></p>
            </div>
          </div>
{acc("入社のきっかけ", "実家が百姓を営んでいて、跡継ぎが兄だったため別で仕事を探していました。<br>手に職をつけたいと思い、親戚が電気工事会社で働いているのをきっかけに私もその道を目指すようになりました。<br>通っていた職業訓練校にこの会社の当時の社長が就職の説明会にきたのをきっかけに入社することを決意しました。")}
{acc("やりがい", "仕事自体楽しいのですがやはり仕事頑張って遊びに行く！仲間と飲みに行く！を楽しみに日々仕事に励んでいました。<br>当時は川崎市以外でも仕事がありいろんな場所で働けるのも楽しかったです。")}
{acc("仕事を通じての思い出", "電気工事は他の業種の方たちとの繋がりもあるので、いろんな人と出会い、そしてよく飲みにいきました。")}
{acc("入社を考えている方々にメッセージ", "仕事は面白いです！電気工事を選んで良かったと今でも思います。電気は世の中から無くならないものですし変わっていくものでもあります。<br>蛍光灯～LED～有機ELと時代の変化と共に次々と生まれる新しい技術にワクワクします。<br>電気自動車なんてものは私が生まれた頃は、想像すらできなかったです。<br>仕事は奥が深く、最初はわからないことも多々あると思いますが、なるべく分かりやすく教えていくので、一緒に楽しく働きましょう！")}
        </article>
        <article class="interview fade-in">
          <div class="interview__head">
            <img class="interview__photo" src="{UP}/2025/05/751a61a62b286f4c0b5a7d85bf13a7a3.png" alt="Nさん" loading="lazy">
            <div>
              <p class="interview__name">Nさん</p>
              <p class="interview__meta">令和6年2月1日入社<br>勤続 <strong data-hire-date="2024-02-01">--年</strong></p>
            </div>
          </div>
{acc("入社動機とこの会社に決めた理由", "前職で電気工事の職人として働いていたのですが、施工管理にチャレンジしたいと思い応募しました。面接の際、社長からいろいろな話を伺いながら社内を案内していただき、雰囲気や福利厚生の良さを感じる事で入社を決意しました。")}
{acc("入社後の感想", "入社前に感じた雰囲気の良さは入社後も変わっていません。コミュニケーションの取りやすい環境だからこそ結束力があり、それが会社の強みとして仕事上でも生きていると感じています。<br>教育面においても、未経験の分野で不安はありましたが、個人の状況や能力を把握したうえで指導していただけるので、不安はすぐに払拭されました。自分自身の成長を感じながら目標に向かっていく充実感はこの会社だからこそ味わえるものだと感じています。")}
{acc("目標", "今は上司や先輩方に色々と教えていただきながら現場作業や管理業務をおこなっていますが、一日でも早く仕事を覚えてこの会社に貢献したいです。")}
{acc("入社を考えている方々にメッセージ", "建設業界はものづくりに対する達成感や、形に残る仕事に対するやりがいを感じることができる業種です。工事が終わった際に、問題なく設備が作動したり取り付けた照明のライトが一斉に点灯したりした時は安堵感や達成感を強く感じます。人々の暮らしに必要不可欠な役割を果たしている貢献度の高い業種ですのでやりがいを感じながら働けます！<br>未経験でも学べる環境が整っています。上司や先輩の方々も親身に相談に乗ってくださるので、安心して働くことができます。一緒に働ける日を楽しみにしています！")}
        </article>
      </div>
      <div class="apply-actions">
        <a class="btn-primary" href="#apply-form">✉ 応募フォームから応募する</a>
        <a class="apply-tel" href="tel:0447556161"><small>電話での応募は</small><span class="num">📞 044-755-6161</span></a>
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
      <iframe class="map-embed" style="margin-top:32px" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        src="https://maps.google.com/maps?q=%E7%A5%9E%E5%A5%88%E5%B7%9D%E7%9C%8C%E5%B7%9D%E5%B4%8E%E5%B8%82%E9%AB%98%E6%B4%A5%E5%8C%BA%E6%98%8E%E6%B4%A514-1&output=embed"
        title="勤務地・面接地 地図"></iframe>
    </div>
  </section>

  <!-- 応募フォーム（WP版は Contact Form 7「求人用フォーム」） -->
  <section class="section section--gray" id="apply-form">
    <div class="container">
      <div class="section__head section__head--center fade-in">
        <span class="section__label">Entry</span>
        <h2 class="section__title">住吉電機株式会社の求人募集に応募する</h2>
      </div>
      <div class="contact-form fade-in">
        <p class="form-note">※静的サイトのためフォームは送信されません。WordPress化の際に Contact Form 7「求人用フォーム」へ接続します。</p>
        <form action="#" method="post">
          <div class="form-row">
            <label for="r-name">氏名<span class="required-tag">必須</span><span class="example">（例）鈴木 太郎</span></label>
            <input type="text" id="r-name" name="your-name" required autocomplete="name">
          </div>
          <div class="form-row">
            <label for="r-furigana">ふりがな<span class="required-tag">必須</span><span class="example">（例）すずき たろう</span></label>
            <input type="text" id="r-furigana" name="furigana" required>
          </div>
          <div class="form-row">
            <label for="r-birth">生年月日<span class="required-tag">必須</span></label>
            <input type="date" id="r-birth" name="birth-date" required>
          </div>
          <div class="form-row">
            <label>性別<span class="optional-tag">任意</span></label>
            <div class="radio-group">
              <label><input type="radio" name="gender" value="男性">男性</label>
              <label><input type="radio" name="gender" value="女性">女性</label>
              <label><input type="radio" name="gender" value="その他">その他</label>
              <label><input type="radio" name="gender" value="回答しない">回答しない</label>
            </div>
          </div>
          <div class="form-row">
            <label for="r-tel">電話番号<span class="required-tag">必須</span><span class="example">（例）090-1234-5678</span></label>
            <input type="tel" id="r-tel" name="phone" required autocomplete="tel">
          </div>
          <div class="form-row">
            <label for="r-email">メールアドレス<span class="required-tag">必須</span></label>
            <input type="email" id="r-email" name="your-email" required autocomplete="email">
          </div>
          <div class="form-row">
            <label for="r-job">現在の職業<span class="required-tag">必須</span></label>
            <select id="r-job" name="job" required>
              <option value="">選択してください</option>
              <option>高校生</option>
              <option>大学生・大学院生・短大生・専門学生</option>
              <option>アルバイト・パート</option>
              <option>フリーター</option>
              <option>正社員</option>
              <option>契約社員</option>
              <option>派遣社員</option>
              <option>主婦(夫)</option>
              <option>無職</option>
              <option>その他</option>
            </select>
          </div>
          <div class="form-submit">
            <button type="submit" class="btn-primary">送信</button>
          </div>
        </form>
        <div class="apply-actions" style="margin-bottom:0">
          <a class="apply-tel" href="tel:0447556161"><small>電話での応募は</small><span class="num">📞 044-755-6161</span></a>
        </div>
      </div>
    </div>
  </section>"""
W["recruit/index.html"] = layout(
    title="求人情報", active="recruit",
    description="【賞与年2回】施工管理者募集！現場作業員募集！未経験者も多数活躍中！寮あり！土日祝日休み！現場は川崎市内のみ！残業少なめ！住吉電機株式会社の求人情報。",
    body=recruit_body)

# ----------------------------------------------------------
# お問い合わせ（WP版は Contact Form 7「お問い合わせフォーム」）
# ※現サイトでは固定ページが非公開設定。フォーム定義はエクスポートに準拠
# ----------------------------------------------------------
contact_body = f"""{page_header("Contact", "お問い合わせ")}
{breadcrumb(("お問い合わせ",))}

  <section class="section">
    <div class="container">
      <div class="contact-form">
        <p class="form-note">※静的サイトのためフォームは送信されません。WordPress化の際に Contact Form 7「お問い合わせフォーム」へ接続します。</p>
        <form action="#" method="post">
          <div class="form-row">
            <label for="c-company">御社名・屋号</label>
            <input type="text" id="c-company" name="company" autocomplete="organization">
          </div>
          <div class="form-row">
            <label for="c-name">お名前<span class="required-tag">必須</span></label>
            <input type="text" id="c-name" name="your-name" required autocomplete="name">
          </div>
          <div class="form-row">
            <label for="c-furigana">ふりがな<span class="required-tag">必須</span></label>
            <input type="text" id="c-furigana" name="furigana" required>
          </div>
          <div class="form-row">
            <label for="c-email">メールアドレス<span class="required-tag">必須</span></label>
            <input type="email" id="c-email" name="your-email" required autocomplete="email">
          </div>
          <div class="form-row">
            <label for="c-tel">電話番号<span class="required-tag">必須</span></label>
            <input type="tel" id="c-tel" name="your-tel" required autocomplete="tel">
          </div>
          <div class="form-row">
            <label for="c-category">お問合せカテゴリー<span class="required-tag">必須</span></label>
            <select id="c-category" name="category" required>
              <option>お問合せ</option>
              <option>見積り依頼</option>
              <option>採用情報</option>
              <option>その他</option>
            </select>
          </div>
          <div class="form-row">
            <label for="c-message">お問い合わせ内容<span class="required-tag">必須</span></label>
            <textarea id="c-message" name="your-message" rows="8" required></textarea>
          </div>
          <div class="privacy-box">
            <p><strong>【お問合せに伴う個人情報の取得、 利用に当たり、ご同意いただきたい事項について】</strong></p>
            <p>■個人情報保護管理者: 住吉電機株式会社 代表取締役 高橋　賢次<br>
            〒213-0044 神奈川県川崎市高津区明津14番地1<br>
            TEL: 044-755-6161（代）<br>
            ■取得した個人情報はお問合せ対応、その他皆様のために利用いたします。<br>
            ■お問合せをいただいた個人情報について、ご利用目的の通知、開示、内容の訂正、追加、または削除、利用の停止、消去、及び第三者への提供の停止を希望される場合は、個人情報保護管理責任者までご連絡ください。<br>
            ■個人情報をご提供いただくのは任意ですが、必要な情報をいただけない場合、お問合せに回答できない場合がございますのでご了承ください。</p>
          </div>
          <div class="agree-row">
            <label><input type="checkbox" name="agree" required>上記内容に同意する</label>
          </div>
          <div class="form-submit">
            <button type="submit" class="btn-primary">送信</button>
          </div>
        </form>
      </div>
    </div>
  </section>"""
W["contact/index.html"] = layout(
    title="お問い合わせ", active="contact",
    description="住吉電機株式会社へのお問い合わせ。お見積り・ご相談はお気軽にどうぞ。TEL: 044-755-6161（代）",
    body=contact_body)

# ----------------------------------------------------------
# ブログ装飾サンプル（デザイン確認用。公開時には削除する）
# ----------------------------------------------------------
styleguide_body = f"""{page_header("Style Guide", "ブログ記事 装飾サンプル")}
{breadcrumb(("装飾サンプル",))}

  <section class="section">
    <div class="container">
      <article class="article">
        <div class="article__meta">
          <time class="article__date" datetime="2026-06-10">2026/06/10</time>
        </div>
        <div class="article__body">
          <p>このページはブログ運用時の見出し・装飾デザインの確認用サンプルです（本番公開時には削除します）。記事の書き出しの段落はこのように表示されます。テキストの行間や文字サイズは読みやすさを重視しています。<strong>強調したいテキスト</strong>にはマーカー風の下線が付きます。<a href="#">テキストリンク</a>はこのような表示です。</p>

          <h2>見出し2（H2）の表示サンプル</h2>
          <p>H2はアンバーの縦バーと薄いグレー帯付きの、記事の大きな区切りに使う見出しです。WordPress化後はエディタで「見出し2」を選ぶだけでこのデザインになります。</p>

          <h3>見出し3（H3）の表示サンプル</h3>
          <p>H3は下線と左端のアンバーアクセント付き。H2のセクション内の小見出しに使います。</p>

          <h4>見出し4（H4）の表示サンプル</h4>
          <p>H4は小さなアンバーの角マーク付き。補足的な見出しに使います。</p>

          <h2>リスト・引用・表のサンプル</h2>

          <h3>箇条書きリスト</h3>
          <ul>
            <li>電気配線工事</li>
            <li>計装工事</li>
            <li>消防施設工事
              <ul>
                <li>自動火災報知設備</li>
                <li>誘導灯設備</li>
              </ul>
            </li>
            <li>電気通信工事</li>
          </ul>

          <h3>番号付きリスト</h3>
          <ol>
            <li>お問い合わせ・ご相談</li>
            <li>現地調査・お見積り</li>
            <li>ご契約・施工</li>
            <li>完成・お引き渡し</li>
          </ol>

          <h3>引用</h3>
          <blockquote>
            <p>引用文はこのように表示されます。参考にした資料や規格からの引用などに使用します。</p>
          </blockquote>

          <h3>表（テーブル）</h3>
          <table>
            <tr><th>項目</th><th>内容</th></tr>
            <tr><td>工事名</td><td>○○施設 電気設備工事</td></tr>
            <tr><td>施工時期</td><td>令和○年○月</td></tr>
            <tr><td>工事内容</td><td>受変電設備の更新、照明設備のLED化</td></tr>
          </table>

          <h3>画像</h3>
          <figure>
            <img src="{UP}/2025/05/20230608_060811683_iOS-768x576-1.jpg" alt="施工写真サンプル">
            <figcaption>キャプション：川崎市新本庁舎 太陽光発電設備</figcaption>
          </figure>

          <hr>
          <p>区切り線（hr）の下のテキスト。記事の締めの段落はこのようになります。</p>
        </div>
      </article>
    </div>
  </section>"""
W["styleguide.html"] = layout(
    title="ブログ記事 装飾サンプル", active="",
    description="ブログ記事の見出し・装飾デザイン確認用サンプルページ。",
    body=styleguide_body)

# ----------------------------------------------------------
# 書き出し
# テンプレート内はルート相対(/news/ など)で記述し、出力時に
# 各ページの階層に応じた相対パスへ変換する。これにより
# GitHub Pages のサブディレクトリ公開(/sumiyoshidenki/)や
# file:// での直接閲覧でも動作する。
# WPテーマ化時は変換せずルート相対のままでよい。
# ----------------------------------------------------------
if __name__ == "__main__":
    for path, content in W.items():
        depth = path.count("/")
        rel = "../" * depth if depth else "./"
        content = content.replace('href="/', f'href="{rel}').replace('src="/', f'src="{rel}')
        full = os.path.join(ROOT, path)
        os.makedirs(os.path.dirname(full), exist_ok=True)
        with open(full, "w", encoding="utf-8") as f:
            f.write(content)
    print(f"generated {len(W)} pages (relative paths)")
