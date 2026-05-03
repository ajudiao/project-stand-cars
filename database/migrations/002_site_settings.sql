CREATE TABLE IF NOT EXISTS site_settings (
  id INT(11) NOT NULL AUTO_INCREMENT,

  -- Configurações Gerais
  site_name VARCHAR(255) DEFAULT 'AutoVendas Premium',
  site_tagline VARCHAR(255) DEFAULT 'Seus Carros de Confiança',
  site_description TEXT DEFAULT 'Bem-vindo à AutoVendas Premium! Somos uma concessionária especializada em automóveis de qualidade com atendimento de excelência.',
  site_phone VARCHAR(50) DEFAULT '(+244) 936145154',
  site_email VARCHAR(255) DEFAULT 'contato@autovendas.com',
  site_address VARCHAR(255) DEFAULT '',
  site_city VARCHAR(100) DEFAULT 'Luanda',
  site_hours VARCHAR(255) DEFAULT 'Segunda a Sábado: 9h - 18h | Domingo: 10h - 17h',
  site_blog_enabled TINYINT(1) DEFAULT 1,
  site_newsletter_enabled TINYINT(1) DEFAULT 1,

  -- SEO
  seo_title VARCHAR(255) DEFAULT 'AutoVendas Premium - Seus Carros de Confiança',
  seo_description TEXT DEFAULT 'Concessionária de automóveis com as melhores opções de carros novos e seminovos',
  seo_keywords VARCHAR(500) DEFAULT 'carros, automóveis, concessionária, compra de carros',
  seo_allow_robots TINYINT(1) DEFAULT 1,

  -- Redes Sociais
  social_facebook VARCHAR(255) DEFAULT '',
  social_instagram VARCHAR(255) DEFAULT '',
  social_twitter VARCHAR(255) DEFAULT '',
  social_youtube VARCHAR(255) DEFAULT '',
  social_whatsapp VARCHAR(50) DEFAULT '',
  social_share_buttons TINYINT(1) DEFAULT 1,

  -- Arquivos
  logo_path VARCHAR(255) DEFAULT '',
  favicon_path VARCHAR(255) DEFAULT '',

  -- Conteúdo das Páginas
  about_title VARCHAR(255) DEFAULT 'Sobre Nós',
  about_content TEXT DEFAULT 'Somos uma concessionária de automóveis dedicada a oferecer os melhores veículos com qualidade e confiança.',
  contact_title VARCHAR(255) DEFAULT 'Entre em Contato',
  contact_content TEXT DEFAULT 'Estamos aqui para ajudar. Entre em contato conosco para mais informações.',

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
