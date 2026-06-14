CREATE TABLE species (
  id CHAR(36) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  latin_name VARCHAR(150) NULL,
  description TEXT NULL,
  ideal_condition VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE sites (
  id CHAR(36) PRIMARY KEY,
  managed_by CHAR(36) NULL,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(255) NOT NULL,
  area_hectares DECIMAL(10,2) DEFAULT 0,
  status ENUM('pending','in_progress','completed','verified') DEFAULT 'pending',
  description TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (managed_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE plots (
  id CHAR(36) PRIMARY KEY,
  site_id CHAR(36) NOT NULL,
  plot_code VARCHAR(50) NOT NULL,
  area_m2 DECIMAL(10,2) DEFAULT 0,
  status ENUM('empty','planted','monitoring','completed') DEFAULT 'empty',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

CREATE TABLE planting_records (
  id CHAR(36) PRIMARY KEY,
  plot_id CHAR(36) NOT NULL,
  species_id CHAR(36) NOT NULL,
  recorded_by CHAR(36) NULL,
  seedling_count INT DEFAULT 0,
  planted_at DATE NOT NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (plot_id) REFERENCES plots(id) ON DELETE CASCADE,
  FOREIGN KEY (species_id) REFERENCES species(id),
  FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE monitoring_logs (
  id CHAR(36) PRIMARY KEY,
  planting_record_id CHAR(36) NOT NULL,
  logged_by CHAR(36) NULL,
  alive_count INT DEFAULT 0,
  dead_count INT DEFAULT 0,
  survival_rate DECIMAL(5,2) DEFAULT 0,
  ai_condition ENUM('healthy','wilting','dead','unknown') NULL,
  ai_health_score DECIMAL(4,2) NULL,
  ai_notes TEXT NULL,
  monitored_at DATE NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (planting_record_id) REFERENCES planting_records(id) ON DELETE CASCADE,
  FOREIGN KEY (logged_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE photos (
  id CHAR(36) PRIMARY KEY,
  monitoring_log_id CHAR(36) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  geotag_lat VARCHAR(50) NULL,
  geotag_lng VARCHAR(50) NULL,
  taken_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (monitoring_log_id) REFERENCES monitoring_logs(id) ON DELETE CASCADE
);

CREATE TABLE complaints (
  id CHAR(36) PRIMARY KEY,
  site_id CHAR(36) NOT NULL,
  submitted_by CHAR(36) NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('pending','reviewed','resolved','rejected') DEFAULT 'pending',
  response TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
  FOREIGN KEY (submitted_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE compliance_reports (
  id CHAR(36) PRIMARY KEY,
  site_id CHAR(36) NOT NULL,
  generated_by CHAR(36) NULL,
  title VARCHAR(200) NOT NULL,
  ai_narrative TEXT NULL,
  period VARCHAR(50) NOT NULL,
  status ENUM('draft','final','submitted') DEFAULT 'draft',
  generated_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
  FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE SET NULL
);
