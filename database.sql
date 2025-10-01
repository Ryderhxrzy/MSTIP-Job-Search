-- ================================
-- CREATE DATABASE
-- ================================
CREATE DATABASE IF NOT EXISTS MSTIP_NEW;
USE MSTIP_NEW;

-- ================================
-- USERS (Graduates, Employers, Admins)
-- ================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) UNIQUE NOT NULL,
    email_address VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('Graduate','Employer','Admin') DEFAULT 'Graduate',
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ================================
-- GRADUATE INFORMATION
-- ================================
CREATE TABLE graduate_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    course VARCHAR(200) NOT NULL,
    year_graduated INT NOT NULL,
    skills TEXT NULL,
    resume VARCHAR(255) NOT NULL,
    linkedin_profile TEXT NULL,
    profile VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id)
);

-- ================================
-- COMPANIES (with profile + cover images + industry)
-- ================================
CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL, -- employer owner
    company_name VARCHAR(150) NOT NULL,
    company_type ENUM('Government','Private') NOT NULL,
    government_agency VARCHAR(150) NULL,
    location VARCHAR(150) NOT NULL,
    website VARCHAR(255) NULL,
    industry VARCHAR(150) NOT NULL, -- ✅ added industry field
    about_company TEXT NULL,
    profile_picture VARCHAR(255) NULL, -- ✅ company logo
    cover_image VARCHAR(255) NULL,     -- ✅ company banner
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ================================
-- JOB LISTINGS
-- ================================
CREATE TABLE job_listings (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    job_position ENUM('Entry Level', 'Junior', 'Mid-Level', 'Senior', 'Managerial') DEFAULT 'Entry Level',
    job_category ENUM('normal','deaf') DEFAULT 'normal', -- ✅ inclusive jobs
    slots_available INT NOT NULL DEFAULT 1,
    salary_range VARCHAR(100),
    job_description TEXT,
    qualifications TEXT,
    job_type_shift ENUM('Full-Time', 'Part-Time') DEFAULT 'Full-Time',
    application_deadline DATE,
    contact_email VARCHAR(150),
    image_url VARCHAR(255),
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Open','Closed') DEFAULT 'Open',
    FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ================================
-- APPLICATIONS
-- ================================
CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    job_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending','Reviewed','Accepted','Rejected') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (job_id) REFERENCES job_listings(job_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_job_id (job_id)
);

-- ================================
-- SAMPLE USERS (Employers)
-- ================================
INSERT INTO users (user_id, email_address, password, user_type)
VALUES 
('E100001','hr@accenture.com','test123','Employer'),
('E100002','careers@globe.com.ph','test123','Employer'),
('E100003','jobs@smc.ph','test123','Employer'),
('E100004','recruitment@ayala.com','test123','Employer'),
('E100005','hiring@jollibee.com','test123','Employer'),
('E100006','careers@puregold.com.ph','test123','Employer'),
('E100007','jobs@doh.gov.ph','test123','Employer'),
('E100008','hr@concentrix.com','test123','Employer'),
('E100009','recruitment@pnp.gov.ph','test123','Employer'),
('E100010','jobs@dost.gov.ph','test123','Employer');

-- ================================
-- SAMPLE COMPANIES (10 entries with industry + long descriptions)
-- ================================
INSERT INTO companies 
(user_id, company_name, company_type, government_agency, location, website, industry, about_company, profile_picture, cover_image)
VALUES
('E100001','Accenture Philippines','Private',NULL,'Quezon City, Metro Manila','https://www.accenture.com/ph-en','Information Technology and Consulting',
'Accenture is a global professional services company with leading capabilities in digital, cloud and security. Combining unmatched experience and specialized skills across more than 40 industries, we offer Strategy and Consulting, Interactive, Technology and Operations services. Our people deliver on the promise of technology and human ingenuity every day, helping clients in the Philippines and around the world to become more competitive and future-ready.',
'images/accenture_logo.jpg','images/accenture_cover.jpg'),

('E100002','Globe Telecom','Private',NULL,'Taguig City, Metro Manila','https://www.globe.com.ph','Telecommunications',
'Globe Telecom is a major provider of telecommunications services in the Philippines. We empower individuals, businesses, and institutions through our mobile, fixed line, broadband, and enterprise solutions. We are committed to creating wonderful digital experiences that enrich the lives of Filipinos, supporting innovation and inclusivity in a rapidly evolving digital world.',
'images/globe_logo.png','images/globe_cover.jpg'),

('E100003','San Miguel Corporation','Private',NULL,'Mandaluyong City, Metro Manila','https://www.sanmiguel.com.ph','Conglomerate (Food, Beverage, Infrastructure, Energy)',
'San Miguel Corporation is one of the Philippines’ largest and most diversified conglomerates with operations in beverages, food, packaging, fuel and oil, power, infrastructure, and property. With over a century of history, SMC has built trusted household brands and continues to play a vital role in national development while maintaining its mission of delivering quality products and services to millions of Filipinos.',
'images/compies/smc_logo.png','images/smc_cover.jpg'),

('E100004','Ayala Corporation','Private',NULL,'Makati City, Metro Manila','https://www.ayala.com.ph','Conglomerate (Real Estate, Banking, Telecom, Power)',
'Ayala Corporation is one of the oldest and most respected conglomerates in the Philippines. With businesses spanning real estate, banking, telecommunications, water, power, healthcare, and education, Ayala has consistently been at the forefront of innovation and nation-building. The company is committed to sustainability and inclusive growth, creating long-term value for stakeholders and the communities it serves.',
'images/ayala_logo.png','images/ayala_cover.jpg'),

('E100005','Jollibee Foods Corporation','Private',NULL,'Pasig City, Metro Manila','https://www.jollibee.com.ph','Food and Beverage (Restaurant Chain)',
'Jollibee Foods Corporation (JFC) is the largest Asian food service company, operating over 6,000 stores worldwide across multiple brands including Jollibee, Chowking, Greenwich, Red Ribbon, Mang Inasal, and Burger King Philippines. Known for its iconic Chickenjoy and strong Filipino heritage, JFC is committed to serving great tasting food and bringing the joy of eating to everyone, everywhere.',
'images/jollibee_logo.png','images/jollibee_cover.jpg'),

('E100006','Puregold Price Club','Private',NULL,'Quezon City, Metro Manila','https://www.puregold.com.ph','Retail and Supermarket',
'Puregold Price Club, Inc. is one of the leading supermarket chains in the Philippines. Established to serve the everyday needs of Filipino families, Puregold provides a wide range of affordable groceries and household essentials. With its rapidly expanding footprint nationwide, the company has become a trusted retail brand known for value, convenience, and quality service.',
'images/puregold_logo.png','images/puregold_cover.jpg'),

('E100007','Department of Health','Government','DOH','Manila, Philippines','https://www.doh.gov.ph','Government (Healthcare Services)',
'The Department of Health (DOH) is the principal health agency of the Philippine government. It is responsible for ensuring access to basic public health services, leading programs on disease prevention, health promotion, and regulation of health services. The DOH works tirelessly to improve healthcare systems, strengthen public hospitals, and respond to health emergencies nationwide.',
'images/doh_logo.png','images/doh_cover.jpg'),

('E100008','Concentrix Philippines','Private',NULL,'Quezon City, Metro Manila','https://www.concentrix.com','Business Process Outsourcing (BPO)',
'Concentrix is a leading global provider of customer experience (CX) solutions and technology. In the Philippines, Concentrix is one of the largest BPO employers, offering services in customer care, technical support, sales, and back-office operations across multiple industries. We focus on creating exceptional experiences for our clients and providing growth opportunities for our employees.',
'images/concentrix_logo.png','images/concentrix_cover.jpg'),

('E100009','Philippine National Police','Government','PNP','Quezon City, Metro Manila','https://www.pnp.gov.ph','Government (Law Enforcement and Public Safety)',
'The Philippine National Police (PNP) is the armed national police force tasked with enforcing the law, preventing and controlling crimes, and maintaining peace and order across the country. Guided by its vision to be a highly capable, effective, and credible police service, the PNP continues to safeguard communities and uphold the rule of law in service to the Filipino people.',
'images/pnp_logo.png','images/pnp_cover.jpg'),

('E100010','Department of Science and Technology','Government','DOST','Taguig City, Metro Manila','https://www.dost.gov.ph','Government (Science and Technology)',
'The Department of Science and Technology (DOST) leads the Philippine government’s initiatives to harness science, technology, and innovation for national progress. It provides scientific and technological services, supports research and development, and drives programs that enhance industry competitiveness. The DOST envisions a technologically advanced nation that is sustainable, resilient, and inclusive.',
'images/dost_logo.png','images/dost_cover.jpg');