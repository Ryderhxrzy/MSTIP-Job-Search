SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS mstip_db;

USE mstip_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL UNIQUE,
    email_address VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('Graduate','Employer','Admin') DEFAULT 'Graduate',
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE graduate_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) DEFAULT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    course VARCHAR(200) NOT NULL,
    year_graduated INT NOT NULL,
    skills TEXT DEFAULT NULL,
    resume VARCHAR(255) NOT NULL,
    linkedin_profile TEXT DEFAULT NULL,
    profile VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id)
);

CREATE TABLE contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Unread','Read','Replied') DEFAULT 'Unread',
    INDEX idx_status (status),
    INDEX idx_submitted_at (submitted_at)
);

CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    company_type ENUM('Government','Private') DEFAULT NULL,
    government_agency VARCHAR(150) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    industry VARCHAR(150) DEFAULT NULL,
    contact_number VARCHAR(20) DEFAULT NULL,
    email_address VARCHAR(255) DEFAULT NULL,
    company_size VARCHAR(50) DEFAULT NULL,
    founded_year INT DEFAULT NULL,
    company_culture TEXT DEFAULT NULL,
    work_environment TEXT DEFAULT NULL,
    benefits TEXT DEFAULT NULL,
    about_company TEXT DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE job_listings (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    job_position ENUM('Entry Level','Junior','Mid-Level','Senior','Managerial') DEFAULT 'Entry Level',
    job_category ENUM('normal','deaf') DEFAULT 'normal',
    slots_available INT DEFAULT 1,
    salary_range VARCHAR(100) DEFAULT NULL,
    job_description TEXT DEFAULT NULL,
    qualifications TEXT DEFAULT NULL,
    job_type_shift ENUM('Full-Time','Part-Time') DEFAULT 'Full-Time',
    application_deadline DATE DEFAULT NULL,
    contact_email VARCHAR(150) DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Open','Closed') DEFAULT 'Open',
    FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    job_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending','Reviewed','Accepted','Rejected') DEFAULT 'Pending',
    remarks VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (job_id) REFERENCES job_listings(job_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_job_id (job_id)
);

CREATE TABLE saved_jobs (
    saved_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    job_id INT NOT NULL,
    saved_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_job (user_id, job_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES job_listings(job_id) ON DELETE CASCADE
);

INSERT INTO users (user_id, email_address, password, user_type) VALUES 
('E100001','hr@accenture.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100002','careers@globe.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100003','jobs@smc.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100004','recruitment@ayala.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100005','hiring@jollibee.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100006','careers@puregold.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100007','jobs@doh.gov.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100008','hr@concentrix.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100009','recruitment@pnp.gov.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100010','jobs@dost.gov.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100011','jobs@sm.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100012','recruitment@bdo.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100013','careers@meralco.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100014','hr@nestle.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100015','jobs@unilever.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100016','careers@pldt.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100017','recruitment@bpi.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100018','jobs@robinsons.com.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100019','hr@abs-cbn.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100020','careers@petron.com','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100021','jobs@dlsu.edu.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100022','recruitment@ateneo.edu','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100023','hr@upmc.edu.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100024','jobs@deped.gov.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer'),
('E100025','careers@bsp.gov.ph','$2y$10$vytlW09CH6LobqhjtMgqGuUH753pK3y3QutoG3ddPHvlb9b740NR2','Employer');

INSERT INTO companies (user_id, company_name, company_type, government_agency, location, website, industry, contact_number, email_address, company_size, founded_year, company_culture, work_environment, benefits, about_company, profile_picture, cover_image) VALUES 
('E100001','Accenture Philippines','Private',NULL,'Quezon City, Metro Manila','https://www.accenture.com/ph-en','Information Technology and Consulting','(02) 555-1234','careers.ph@accenture.com','50,000+',1989,'Innovation-driven, diverse, inclusive','Fast-paced, collaborative, technology-focused','Health insurance, training, remote work options','Accenture is a global professional services company with leading capabilities in digital, cloud and security. Combining unmatched experience and specialized skills across more than 40 industries, we offer Strategy and Consulting, Interactive, Technology and Operations services. Our people deliver on the promise of technology and human ingenuity every day, helping clients in the Philippines and around the world to become more competitive and future-ready.','accenture_logo.jpg','accenture_cover.png'),
('E100002','Globe Telecom','Private',NULL,'Taguig City, Metro Manila','https://www.globe.com.ph','Telecommunications','(02) 730-1000','info@globe.com.ph','8,000+',1935,'Customer-focused, innovative, agile','Dynamic, inclusive, growth-oriented','Telco perks, health benefits, employee discounts','Globe Telecom is a major provider of telecommunications services in the Philippines. We empower individuals, businesses, and institutions through our mobile, fixed line, broadband, and enterprise solutions. We are committed to creating wonderful digital experiences that enrich the lives of Filipinos, supporting innovation and inclusivity in a rapidly evolving digital world.','globe_logo.png','globe_cover.png'),
('E100003','San Miguel Corporation','Private',NULL,'Mandaluyong City, Metro Manila','https://www.sanmiguel.com.ph','Conglomerate (Food, Beverage, Infrastructure, Energy)','(02) 8632-3000','info@sanmiguel.com.ph','25,000+',1890,'Legacy-driven, diverse industries, nation-building','Corporate, structured, large-scale operations','Comprehensive health, retirement, product discounts','San Miguel Corporation is one of the Philippines largest and most diversified conglomerates with operations in beverages, food, packaging, fuel and oil, power, infrastructure, and property. With over a century of history, SMC has built trusted household brands and continues to play a vital role in national development while maintaining its mission of delivering quality products and services to millions of Filipinos.','smc_logo.png','smc_cover.png'),
('E100004','Ayala Corporation','Private',NULL,'Makati City, Metro Manila','https://www.ayala.com.ph','Conglomerate (Real Estate, Banking, Telecom, Power)','(02) 7730-1000','info@ayala.com.ph','35,000+',1834,'Sustainability, innovation, leadership','Corporate, strategic, collaborative','Health insurance, scholarships, stock options','Ayala Corporation is one of the oldest and most respected conglomerates in the Philippines. With businesses spanning real estate, banking, telecommunications, water, power, healthcare, and education, Ayala has consistently been at the forefront of innovation and nation-building. The company is committed to sustainability and inclusive growth, creating long-term value for stakeholders and the communities it serves.','ayala_logo.webp','ayala_cover.png'),
('E100005','Jollibee Foods Corporation','Private',NULL,'Pasig City, Metro Manila','https://www.jollibee.com.ph','Food and Beverage (Restaurant Chain)','(02) 8634-1111','info@jfc.com.ph','15,000+',1978,'Family-oriented, Filipino values, customer-first','Friendly, service-oriented, growth-focused','Meal discounts, health coverage, training programs','Jollibee Foods Corporation (JFC) is the largest Asian food service company, operating over 6,000 stores worldwide across multiple brands including Jollibee, Chowking, Greenwich, Red Ribbon, Mang Inasal, and Burger King Philippines. Known for its iconic Chickenjoy and strong Filipino heritage, JFC is committed to serving great tasting food and bringing the joy of eating to everyone, everywhere.','jollibee_logo.png','jollibee_cover.jpg'),
('E100006','Puregold Price Club','Private',NULL,'Quezon City, Metro Manila','https://www.puregold.com.ph','Retail and Supermarket','(02) 8912-8888','customerservice@puregold.com.ph','5,000+',1998,'Value-driven, community-focused','Retail-oriented, customer-first, fast-paced','Employee discounts, health plans, bonuses','Puregold Price Club, Inc. is one of the leading supermarket chains in the Philippines. Established to serve the everyday needs of Filipino families, Puregold provides a wide range of affordable groceries and household essentials. With its rapidly expanding footprint nationwide, the company has become a trusted retail brand known for value, convenience, and quality service.','puregold_logo.webp','puregold_cover.png'),
('E100007','Department of Health','Government','DOH','Manila, Philippines','https://www.doh.gov.ph','Government (Healthcare Services)','(02) 8651-7800','info@doh.gov.ph','10,000+',1898,'Public service, health-focused','Government office, community service','Government health benefits, retirement, allowances','The Department of Health (DOH) is the principal health agency of the Philippine government. It is responsible for ensuring access to basic public health services, leading programs on disease prevention, health promotion, and regulation of health services. The DOH works tirelessly to improve healthcare systems, strengthen public hospitals, and respond to health emergencies nationwide.','doh_logo.svg','doh_cover.avif'),
('E100008','Concentrix Philippines','Private',NULL,'Quezon City, Metro Manila','https://www.concentrix.com','Business Process Outsourcing (BPO)','(02) 7777-7777','careers@concentrix.com','80,000+',2007,'Customer-focused, people-first, global mindset','BPO, energetic, supportive teams','HMO coverage, allowances, career development','Concentrix is a leading global provider of customer experience (CX) solutions and technology. In the Philippines, Concentrix is one of the largest BPO employers, offering services in customer care, technical support, sales, and back-office operations across multiple industries. We focus on creating exceptional experiences for our clients and providing growth opportunities for our employees.','concentrix_logo.jpg','concentrix_cover.jpg'),
('E100009','Philippine National Police','Government','PNP','Quezon City, Metro Manila','https://www.pnp.gov.ph','Government (Law Enforcement and Public Safety)','(02) 8723-0401','info@pnp.gov.ph','220,000+',1991,'Service, honor, justice','Law enforcement, field and office work','Government benefits, hazard pay, retirement','The Philippine National Police (PNP) is the armed national police force tasked with enforcing the law, preventing and controlling crimes, and maintaining peace and order across the country. Guided by its vision to be a highly capable, effective, and credible police service, the PNP continues to safeguard communities and uphold the rule of law in service to the Filipino people.','pnp_logo.png','pnp_cover.jpg'),
('E100010','Department of Science and Technology','Government','DOST','Taguig City, Metro Manila','https://www.dost.gov.ph','Government (Science and Technology)','(02) 8837-2071','info@dost.gov.ph','5,000+',1958,'Research-driven, innovation-focused','Government office, scientific research','Research grants, government benefits, training','The Department of Science and Technology (DOST) leads the Philippine government initiatives to harness science, technology, and innovation for national progress. It provides scientific and technological services, supports research and development, and drives programs that enhance industry competitiveness. The DOST envisions a technologically advanced nation that is sustainable, resilient, and inclusive.','dost_logo.png','dost_cover.jpg'),


('E100011','SM Investments Corporation','Private',NULL,'Pasay City, Metro Manila','https://www.sminvestments.com','Conglomerate (Retail, Banking, Property)','(02) 8857-0100','info@sminvestments.com','50,000+',1960,'Customer-focused, innovative, community-oriented','Corporate, retail, diverse operations','Health insurance, employee discounts, training','SM Investments Corporation is one of the leading integrated property, retail, and banking conglomerates in Southeast Asia. With businesses spanning shopping malls, supermarkets, department stores, banks, and real estate development, SM has become a household name in the Philippines.','sm_logo.png','sm_cover.jpg'),
('E100012','BDO Unibank','Private',NULL,'Makati City, Metro Manila','https://www.bdo.com.ph','Banking and Financial Services','(02) 8631-8000','customercare@bdo.com.ph','35,000+',1968,'Professional, client-centric, excellence-driven','Corporate banking, customer-focused','Competitive salary, health benefits, career growth','BDO Unibank is the largest bank in the Philippines in terms of assets, loans, deposits, and branch network. We provide comprehensive financial products and services to retail and corporate clients.','bdo_logo.webp','bdo_cover.png'),
('E100013','Meralco','Private',NULL,'Pasig City, Metro Manila','https://www.meralco.com.ph','Power Distribution and Utilities','(02) 16211','customerservice@meralco.com.ph','12,000+',1903,'Service excellence, sustainability, innovation','Utility company, field and office work','HMO, retirement plans, employee assistance','Manila Electric Company (Meralco) is the Philippines largest electric distribution company, serving over 7 million customers in Metro Manila and surrounding provinces.','meralco_logo.png','meralco_cover.webp'),
('E100014','Nestlé Philippines','Private',NULL,'Cabuyao, Laguna','https://www.nestle.com.ph','Food and Beverage Manufacturing','(049) 502-5000','consumer.services@ph.nestle.com','4,000+',1911,'Nutrition, wellness, sustainability','Manufacturing, quality-focused','Health coverage, training, work-life balance','Nestlé Philippines is part of the worlds leading nutrition, health and wellness company. We manufacture and distribute trusted brands that bring quality nutrition to Filipino families.','nestle_logo.jpg','nestle_cover.jpg'),
('E100015','Unilever Philippines','Private',NULL,'Pasig City, Metro Manila','https://www.unilever.com.ph','Consumer Goods Manufacturing','(02) 8884-0000','info@unilever.com','3,500+',1927,'Purpose-driven, sustainable, diverse','Fast-paced, innovative, collaborative','Comprehensive benefits, learning programs','Unilever Philippines manufactures and markets consumer products in home care, personal care, and foods. We are committed to making sustainable living commonplace.','unilever_logo.jpg','unilever_cover.jpeg'),
('E100016','PLDT Inc.','Private',NULL,'Makati City, Metro Manila','https://www.pldt.com','Telecommunications and Digital Services','171','customercare@pldt.com','20,000+',1928,'Digital innovation, customer-first','Technology-driven, dynamic','Telco benefits, health insurance, training','PLDT is the leading telecommunications and digital services provider in the Philippines, offering fixed line, wireless, data communications and digital platforms.','pldt_logo.png','pldt_cover.jpg'),
('E100017','Bank of the Philippine Islands','Private',NULL,'Makati City, Metro Manila','https://www.bpi.com.ph','Banking and Financial Services','(02) 889-10000','contact@bpi.com.ph','18,000+',1851,'Integrity, excellence, customer focus','Banking, professional environment','Competitive compensation, health benefits','BPI is the first bank in the Philippines and Southeast Asia. We provide comprehensive banking and financial solutions to individuals and businesses.','bpi_logo.png','bpi_cover.png'),
('E100018','Robinsons Retail Holdings','Private',NULL,'Pasig City, Metro Manila','https://www.robinsonsretailholdings.com.ph','Retail and Supermarket','(02) 8633-7777','info@robinsons.com.ph','25,000+',1980,'Customer satisfaction, efficiency','Retail, fast-paced, service-oriented','Store discounts, health benefits, bonuses','Robinsons Retail Holdings operates supermarkets, department stores, DIY stores, and specialty retail formats across the Philippines.','robinsons_logo.png','robinsons_cover.png'),
('E100019','ABS-CBN Corporation','Private',NULL,'Quezon City, Metro Manila','https://www.abs-cbn.com','Media and Entertainment','(02) 8415-2272','feedback@abs-cbn.com','10,000+',1946,'Creative, innovative, storytelling','Media production, creative environment','Health coverage, creative freedom, training','ABS-CBN is the Philippines leading media and entertainment company, producing content for television, radio, digital platforms, and cinema.','abscbn_logo.svg','abscbn_cover.svg'),
('E100020','Petron Corporation','Private',NULL,'Makati City, Metro Manila','https://www.petron.com','Oil and Gas/Energy','(02) 8884-9200','customercare@petron.com','5,000+',1933,'Safety-first, excellence, sustainability','Industrial, field and office operations','Fuel benefits, health insurance, training','Petron is the largest oil refining and marketing company in the Philippines, providing quality fuel and petroleum products nationwide.','petron_logo.png','petron_cover.avif'),
('E100021','De La Salle University','Private',NULL,'Manila, Philippines','https://www.dlsu.edu.ph','Higher Education','(02) 8524-4611','inquire@dlsu.edu.ph','5,000+',1911,'Lasallian values, excellence, service','Academic, research-focused','Educational benefits, health insurance','DLSU is a leading Catholic university in the Philippines committed to excellence in teaching, research, and community service.','dlsu_logo.png','dlsu_cover.jpg'),
('E100022','Ateneo de Manila University','Private',NULL,'Quezon City, Metro Manila','https://www.ateneo.edu','Higher Education','(02) 8426-6001','info@ateneo.edu','4,500+',1859,'Jesuit education, excellence, service','Academic, collaborative, faith-based','Tuition support, health benefits, development','Ateneo is a premier Jesuit university providing holistic education and forming leaders of competence, conscience, and compassion.','ateneo_logo.png','ateneo_cover.png'),
('E100023','University of the Philippines Manila','Government','UP System','Manila, Philippines','https://www.upm.edu.ph','Higher Education and Healthcare','(02) 8554-8400','info@up.edu.ph','6,000+',1908,'Academic excellence, public service','Academic, research hospital environment','Government benefits, academic freedom','UP Manila is the Philippines leading center for health sciences education and the National Health Sciences Center.','upm_logo.png','upm_cover.webp'),
('E100024','Department of Education','Government','DepEd','Pasig City, Metro Manila','https://www.deped.gov.ph','Government (Basic Education)','(02) 8632-6585','info@deped.gov.ph','900,000+',1863,'Learner-centered, inclusive, quality education','Schools, offices, field work','Government benefits, summer break, allowances','DepEd formulates and implements policies for the Philippine basic education system. We provide quality, accessible education to all Filipino learners.','deped_logo.webp','deped_cover.jpg'),
('E100025','Bangko Sentral ng Pilipinas','Government','BSP','Manila, Philippines','https://www.bsp.gov.ph','Government (Central Banking)','(02) 8708-7701','info@bsp.gov.ph','4,000+',1993,'Integrity, excellence, dynamism','Banking, regulatory, economic policy','Government benefits, training, research grants','BSP is the central bank of the Philippines. We promote price stability, financial system stability, and a safe and efficient payments system.','bsp_logo.png','bsp_cover.webp');

INSERT INTO job_listings (company_id, job_title, job_position, job_category, slots_available, salary_range, job_description, qualifications, job_type_shift, application_deadline, contact_email) VALUES 
(1,'Software Developer','Junior','normal',5,'₱35,000 - ₱50,000','We are looking for passionate Software Developers to join our development team. You will be responsible for designing, coding, and modifying software applications according to client specifications.','Bachelor degree in Computer Science or related field; Knowledge of programming languages such as Java, Python, or C#; Strong problem-solving skills','Full-Time','2025-11-30','careers@accenture.com'),
(1,'Business Analyst','Mid-Level','normal',3,'₱50,000 - ₱70,000','Join our team as a Business Analyst where you will bridge the gap between IT and business. You will analyze business processes and recommend technology solutions.','3+ years experience in business analysis; Excellent communication skills; Proficiency in data analysis tools; Bachelor degree in Business or IT','Full-Time','2025-11-30','careers@accenture.com'),
(1,'Data Scientist','Senior','normal',2,'₱80,000 - ₱120,000','We need an experienced Data Scientist to analyze large datasets and develop predictive models that drive business decisions.','Master degree in Data Science or related field; 5+ years experience; Proficiency in Python, R, and SQL; Experience with machine learning algorithms','Full-Time','2025-12-15','careers@accenture.com'),
(1,'UX/UI Designer','Junior','deaf',3,'₱30,000 - ₱45,000','Design intuitive and engaging user interfaces for web and mobile applications. This position welcomes deaf and hard-of-hearing candidates with full accessibility support.','Portfolio showcasing design work; Proficiency in Figma, Adobe XD, or Sketch; Understanding of user-centered design principles; Sign language support available','Full-Time','2025-11-25','careers@accenture.com'),
(1,'Project Manager','Managerial','normal',2,'₱90,000 - ₱130,000','Lead cross-functional teams in delivering technology projects on time and within budget. Manage project scope, resources, and stakeholder communications.','PMP certification preferred; 7+ years project management experience; Strong leadership skills; Experience with Agile/Scrum methodologies','Full-Time','2025-12-10','careers@accenture.com'),
(2,'Network Engineer','Mid-Level','normal',4,'₱45,000 - ₱65,000','Maintain and optimize our telecommunications network infrastructure. Troubleshoot network issues and implement improvements.','3+ years experience in network engineering; CCNA or CCNP certification; Knowledge of routing, switching, and network security','Full-Time','2025-11-28','careers@globe.com.ph'),
(2,'Customer Service Representative','Entry Level','deaf',8,'₱18,000 - ₱25,000','Provide excellent customer support through chat and email channels. Inclusive workplace with full accessibility for deaf employees.','High school diploma or equivalent; Excellent written communication; Basic computer skills; Sign language interpreters provided','Full-Time','2025-11-20','careers@globe.com.ph'),
(2,'Digital Marketing Specialist','Junior','normal',3,'₱30,000 - ₱45,000','Execute digital marketing campaigns across social media, email, and web channels. Analyze campaign performance and optimize strategies.','Bachelor degree in Marketing or related field; Experience with Google Analytics and social media platforms; Creative thinking skills','Full-Time','2025-12-05','careers@globe.com.ph'),
(2,'IT Security Analyst','Senior','normal',2,'₱70,000 - ₱100,000','Protect our telecommunications infrastructure from cyber threats. Monitor security systems and respond to incidents.','5+ years in cybersecurity; CISSP or CEH certification preferred; Knowledge of security frameworks and threat intelligence','Full-Time','2025-12-15','careers@globe.com.ph'),
(2,'Retail Store Manager','Managerial','normal',5,'₱40,000 - ₱60,000','Manage Globe retail stores and lead sales teams to achieve targets. Ensure excellent customer experience and store operations.','3+ years retail management experience; Strong leadership and sales skills; Customer service oriented','Full-Time','2025-11-30','careers@globe.com.ph'),
(1,'Quality Assurance Tester','Entry Level','deaf',2,'₱25,000 - ₱35,000','Test software applications to ensure quality and functionality. Deaf-friendly environment with visual communication tools and sign language support.','Basic understanding of software testing; Attention to detail; Good written communication; Fresh graduates welcome','Full-Time','2025-11-22','careers@accenture.com'),
(1,'Cloud Solutions Architect','Senior','normal',2,'₱100,000 - ₱150,000','Design and implement cloud infrastructure solutions for enterprise clients using AWS, Azure, or Google Cloud.','5+ years experience in cloud architecture; AWS/Azure certifications; Strong technical leadership skills','Full-Time','2025-12-20','careers@accenture.com'),
(1,'Cybersecurity Consultant','Mid-Level','normal',3,'₱60,000 - ₱85,000','Assess client security posture and implement security solutions to protect against cyber threats.','3+ years in cybersecurity; CISSP, CEH, or equivalent certification; Knowledge of security frameworks','Full-Time','2025-12-01','careers@accenture.com'),

-- Globe Telecom (company_id: 2) - Adding 5 more jobs (2 deaf, 3 normal)
(2,'Technical Support Specialist','Entry Level','deaf',4,'₱20,000 - ₱28,000','Provide technical support via chat and email. Fully accessible workplace for deaf employees with assistive technology.','Tech-savvy; Strong written communication; Problem-solving skills; Sign language support available','Full-Time','2025-11-25','careers@globe.com.ph'),
(2,'Telecommunications Engineer','Senior','normal',2,'₱75,000 - ₱105,000','Design and optimize telecommunications systems and infrastructure for improved service delivery.','Bachelor in Telecommunications/Electrical Engineering; 5+ years experience; Strong analytical skills','Full-Time','2025-12-10','careers@globe.com.ph'),
(2,'Sales Executive','Junior','normal',6,'₱25,000 - ₱40,000 + Commission','Drive sales of Globe products and services through B2B and B2C channels. Meet sales targets and build customer relationships.','Bachelor degree preferred; Sales experience is a plus; Excellent communication skills; Goal-oriented','Full-Time','2025-11-28','careers@globe.com.ph'),

-- San Miguel Corporation (company_id: 3) - Adding 6 jobs (2 deaf, 4 normal)
(3,'Production Operator','Entry Level','deaf',5,'₱18,000 - ₱25,000','Operate production machinery in our manufacturing facilities. Inclusive workplace with visual safety systems and deaf support.','High school diploma; Willingness to work in manufacturing; Safety-conscious; Sign language interpreters available','Full-Time','2025-11-20','jobs@smc.ph'),
(3,'Supply Chain Analyst','Mid-Level','normal',3,'₱45,000 - ₱65,000','Analyze supply chain operations and optimize logistics for cost efficiency and timely delivery.','3+ years in supply chain; Bachelor in Business/Logistics; Proficiency in SAP or similar ERP systems','Full-Time','2025-12-05','jobs@smc.ph'),
(3,'Electrical Engineer','Junior','normal',4,'₱30,000 - ₱45,000','Maintain and troubleshoot electrical systems in our power and infrastructure facilities.','Licensed Electrical Engineer; Fresh graduates welcome; Knowledge of electrical systems and safety protocols','Full-Time','2025-11-30','jobs@smc.ph'),
(3,'Financial Analyst','Mid-Level','normal',2,'₱50,000 - ₱70,000','Prepare financial reports, conduct analysis, and support strategic business decisions.','CPA or finance degree; 3+ years experience; Advanced Excel skills; Strong analytical abilities','Full-Time','2025-12-15','jobs@smc.ph'),

-- Ayala Corporation (company_id: 4) - Adding 6 jobs (2 deaf, 4 normal)
(4,'Graphic Designer','Junior','deaf',3,'₱28,000 - ₱40,000','Create visual content for marketing campaigns and corporate communications. Deaf-inclusive creative team.','Portfolio required; Proficiency in Adobe Creative Suite; Creative mindset; Sign language support provided','Full-Time','2025-11-25','recruitment@ayala.com'),
(4,'Real Estate Analyst','Mid-Level','normal',2,'₱55,000 - ₱75,000','Analyze real estate markets, evaluate property investments, and support acquisition decisions.','3+ years in real estate/finance; Strong analytical and financial modeling skills; Bachelor in relevant field','Full-Time','2025-12-08','recruitment@ayala.com'),
(4,'Sustainability Manager','Senior','normal',2,'₱80,000 - ₱110,000','Lead sustainability initiatives across Ayala businesses, focusing on ESG goals and climate action.','5+ years in sustainability/CSR; Bachelor or Master in Environmental Science or related; Strong leadership','Full-Time','2025-12-20','recruitment@ayala.com'),
(4,'Investment Associate','Mid-Level','normal',2,'₱60,000 - ₱90,000','Evaluate investment opportunities and support portfolio management for Ayala group investments.','CFA candidate preferred; 3+ years in investment banking/PE; Strong financial analysis skills','Full-Time','2025-12-10','recruitment@ayala.com'),

-- Jollibee Foods Corporation (company_id: 5) - Adding 6 jobs (2 deaf, 4 normal)
(5,'Store Crew','Entry Level','deaf',10,'₱15,000 - ₱20,000','Serve customers and maintain store operations. Deaf-friendly stores with visual communication systems.','High school graduate; Customer service oriented; Willing to work shifts; Sign language training provided','Full-Time','2025-11-18','hiring@jollibee.com'),
(5,'Operations Manager','Managerial','normal',4,'₱50,000 - ₱75,000','Manage restaurant operations, lead teams, ensure quality standards, and drive sales performance.','3+ years restaurant management experience; Strong leadership; Food safety certification','Full-Time','2025-12-01','hiring@jollibee.com'),
(5,'Food Technologist','Mid-Level','normal',2,'₱40,000 - ₱60,000','Develop and improve food products, ensuring quality, safety, and consistency across our brands.','Bachelor in Food Technology/Science; 2+ years experience; Knowledge of food safety standards','Full-Time','2025-12-12','hiring@jollibee.com'),
(5,'Marketing Manager','Senior','normal',2,'₱70,000 - ₱100,000','Lead marketing strategies for Jollibee brands, manage campaigns, and drive brand growth.','5+ years marketing experience; Strong digital marketing skills; Creative and strategic thinker','Full-Time','2025-12-15','hiring@jollibee.com'),

-- Puregold (company_id: 6) - Adding 6 jobs (2 deaf, 4 normal)
(6,'Cashier','Entry Level','deaf',8,'₱16,000 - ₱22,000','Process customer transactions efficiently. Inclusive workplace with deaf support and assistive technology.','High school graduate; Basic math skills; Customer-focused; Sign language support available','Full-Time','2025-11-15','careers@puregold.com.ph'),
(6,'Store Supervisor','Junior','normal',5,'₱25,000 - ₱35,000','Supervise store operations, manage inventory, and ensure excellent customer service.','1-2 years retail experience; Leadership skills; Good communication; Bachelor degree preferred','Full-Time','2025-11-28','careers@puregold.com.ph'),
(6,'Procurement Officer','Mid-Level','normal',3,'₱35,000 - ₱50,000','Source products, negotiate with suppliers, and manage procurement processes for store operations.','3+ years procurement experience; Negotiation skills; Knowledge of retail supply chain','Full-Time','2025-12-05','careers@puregold.com.ph'),
(6,'HR Generalist','Mid-Level','normal',2,'₱35,000 - ₱50,000','Handle recruitment, employee relations, benefits administration, and HR compliance.','3+ years HR experience; Knowledge of labor laws; Strong interpersonal skills','Full-Time','2025-12-10','careers@puregold.com.ph'),

-- Department of Health (company_id: 7) - Adding 6 jobs (2 deaf, 4 normal)
(7,'Medical Records Clerk','Entry Level','deaf',4,'₱18,000 - ₱25,000','Organize and maintain patient medical records. Accessible government workplace for deaf employees.','Associate degree or relevant certification; Detail-oriented; Basic computer skills; Sign language support','Full-Time','2025-11-22','jobs@doh.gov.ph'),
(7,'Public Health Nurse','Junior','normal',6,'₱25,000 - ₱35,000','Provide community health services, conduct health education, and support disease prevention programs.','Licensed Nurse; Fresh graduates welcome; Community health orientation; Willing to do field work','Full-Time','2025-12-01','jobs@doh.gov.ph'),
(7,'Health Program Officer','Mid-Level','normal',3,'₱40,000 - ₱60,000','Implement and monitor DOH health programs, coordinate with local health units, and prepare reports.','Bachelor in Public Health or related; 3+ years experience; Strong project management skills','Full-Time','2025-12-15','jobs@doh.gov.ph'),
(7,'Epidemiologist','Senior','normal',2,'₱65,000 - ₱90,000','Investigate disease outbreaks, analyze health data, and develop disease surveillance systems.','Master in Epidemiology or Public Health; 5+ years experience; Strong analytical and research skills','Full-Time','2025-12-20','jobs@doh.gov.ph'),

-- Concentrix (company_id: 8) - Adding 6 jobs (2 deaf, 4 normal)
(8,'Content Moderator','Entry Level','deaf',6,'₱20,000 - ₱28,000','Review and moderate online content according to client guidelines. Deaf-accessible BPO environment.','High school graduate; Good written English; Attention to detail; Sign language interpreters provided','Full-Time','2025-11-18','careers@concentrix.com'),
(8,'Team Leader','Junior','normal',5,'₱30,000 - ₱45,000','Lead a team of customer service representatives, monitor performance, and ensure quality service delivery.','1-2 years BPO experience; Leadership potential; Excellent communication; Bachelor degree preferred','Full-Time','2025-11-25','careers@concentrix.com'),
(8,'Training Specialist','Mid-Level','normal',3,'₱40,000 - ₱60,000','Design and deliver training programs for new hires and continuous development of BPO staff.','3+ years training experience; Strong presentation skills; Knowledge of adult learning principles','Full-Time','2025-12-05','careers@concentrix.com'),
(8,'Operations Manager','Senior','normal',2,'₱80,000 - ₱120,000','Oversee BPO operations, manage large teams, ensure SLA compliance, and drive operational excellence.','7+ years BPO management experience; Strong leadership; Data-driven decision maker','Full-Time','2025-12-18','careers@concentrix.com'),

-- Philippine National Police (company_id: 9) - Adding 4 jobs (1 deaf, 3 normal)
(9,'Administrative Assistant','Entry Level','deaf',3,'₱18,000 - ₱25,000 + Government Benefits','Provide administrative support to PNP offices. Accessible government workplace with deaf accommodations.','Associate degree; Good organizational skills; Computer literate; Sign language support available','Full-Time','2025-11-20','recruitment@pnp.gov.ph'),
(9,'Intelligence Analyst','Mid-Level','normal',3,'₱35,000 - ₱50,000 + Government Benefits','Analyze intelligence data, prepare reports, and support law enforcement operations.','Bachelor in Criminology or related; 2+ years experience; Strong analytical skills; Background check required','Full-Time','2025-12-01','recruitment@pnp.gov.ph'),
(9,'IT Support Specialist','Junior','normal',4,'₱25,000 - ₱38,000 + Government Benefits','Maintain PNP IT systems, troubleshoot technical issues, and support digital infrastructure.','Bachelor in IT/Computer Science; Fresh graduates welcome; Hardware/software troubleshooting skills','Full-Time','2025-11-28','recruitment@pnp.gov.ph'),

-- Department of Science and Technology (company_id: 10) - Adding 5 jobs (2 deaf, 3 normal)
(10,'Research Assistant','Entry Level','deaf',3,'₱20,000 - ₱28,000 + Government Benefits','Support research projects with data collection and analysis. Inclusive research environment for deaf professionals.','Bachelor in Science or Engineering; Research-oriented; Good written skills; Sign language support','Full-Time','2025-11-25','jobs@dost.gov.ph'),
(10,'Science Research Specialist','Mid-Level','normal',3,'₱40,000 - ₱60,000 + Government Benefits','Lead research projects in various scientific fields, publish findings, and contribute to S&T development.','Master degree in relevant science field; 3+ years research experience; Publications preferred','Full-Time','2025-12-10','jobs@dost.gov.ph'),
(10,'Technology Transfer Officer','Junior','normal',2,'₱30,000 - ₱45,000 + Government Benefits','Facilitate technology transfer from research to industry, coordinate with partners, and promote innovations.','Bachelor in Science/Engineering/Business; Good communication skills; Interest in innovation','Full-Time','2025-12-05','jobs@dost.gov.ph'),

-- New Companies Job Listings (company_id 11-25)
-- SM Investments (company_id: 11) - 4 jobs (1 deaf, 3 normal)
(11,'Visual Merchandiser','Junior','deaf',2,'₱22,000 - ₱32,000','Design and arrange store displays to attract customers. Creative deaf-friendly environment.','Portfolio of design work; Creative eye; Retail experience preferred; Sign language support','Full-Time','2025-11-28','jobs@sm.com.ph'),
(11,'Mall Operations Officer','Mid-Level','normal',3,'₱40,000 - ₱60,000','Oversee daily mall operations, coordinate with tenants, and ensure optimal customer experience.','3+ years mall/retail management; Strong organizational skills; Customer service oriented','Full-Time','2025-12-05','jobs@sm.com.ph'),
(11,'Property Development Associate','Junior','normal',2,'₱35,000 - ₱50,000','Support property development projects from planning to execution for SM malls and residential projects.','Bachelor in Civil Engineering/Architecture; Fresh graduates or 1-2 years experience; Project management interest','Full-Time','2025-12-10','jobs@sm.com.ph'),

-- BDO Unibank (company_id: 12) - 4 jobs (1 deaf, 3 normal)
(12,'Data Entry Specialist','Entry Level','deaf',4,'₱18,000 - ₱26,000','Process banking documents and data entry tasks. Deaf-accessible banking workplace with full support.','High school graduate; Accurate typing skills; Attention to detail; Sign language interpreters available','Full-Time','2025-11-20','recruitment@bdo.com.ph'),
(12,'Relationship Manager','Mid-Level','normal',4,'₱50,000 - ₱80,000','Manage high-value client relationships, provide banking solutions, and drive business growth.','3+ years banking experience; Sales and relationship management skills; Bachelor in Finance/Business','Full-Time','2025-12-01','recruitment@bdo.com.ph'),
(12,'Credit Analyst','Junior','normal',3,'₱30,000 - ₱45,000','Evaluate loan applications, assess credit risk, and prepare credit recommendations.','Bachelor in Finance/Accounting; Strong analytical skills; Fresh graduates welcome','Full-Time','2025-11-28','recruitment@bdo.com.ph'),

-- Meralco (company_id: 13) - 4 jobs (1 deaf, 3 normal)
(13,'Billing Analyst','Entry Level','deaf',3,'₱20,000 - ₱30,000','Process customer billing information and resolve billing inquiries. Accessible utility company environment.','Associate degree; Detail-oriented; Basic accounting knowledge; Sign language support available','Full-Time','2025-11-22','careers@meralco.com.ph'),
(13,'Electrical Maintenance Engineer','Mid-Level','normal',4,'₱45,000 - ₱65,000','Maintain and repair electrical distribution systems and ensure reliable power supply.','Licensed Electrical Engineer; 3+ years utility/power industry experience; Willing to do field work','Full-Time','2025-12-05','careers@meralco.com.ph'),
(13,'Customer Service Representative','Junior','normal',5,'₱22,000 - ₱32,000','Handle customer inquiries, complaints, and service requests via multiple channels.','Bachelor degree; Excellent communication skills; Customer service experience preferred','Full-Time','2025-11-25','careers@meralco.com.ph'),

-- Nestlé Philippines (company_id: 14) - 4 jobs (1 deaf, 3 normal)
(14,'Production Associate','Entry Level','deaf',5,'₱18,000 - ₱26,000','Support food production operations in our manufacturing facilities. Deaf-inclusive workplace with visual systems.','High school graduate; Manufacturing interest; Safety-conscious; Sign language support provided','Full-Time','2025-11-18','jobs@nestle.com.ph'),
(14,'Brand Manager','Senior','normal',2,'₱80,000 - ₱120,000','Lead brand strategy and marketing initiatives for Nestlé product portfolio in the Philippines.','5+ years brand management; Strong marketing and leadership skills; FMCG experience required','Full-Time','2025-12-15','jobs@nestle.com.ph'),
(14,'Quality Control Specialist','Mid-Level','normal',3,'₱35,000 - ₱50,000','Ensure product quality through testing, inspection, and compliance with food safety standards.','Bachelor in Food Science/Chemistry; 2+ years QC experience; Knowledge of food safety regulations','Full-Time','2025-12-01','jobs@nestle.com.ph'),

-- Unilever Philippines (company_id: 15) - 4 jobs (1 deaf, 3 normal)
(15,'Packaging Designer','Junior','deaf',2,'₱28,000 - ₱42,000','Create innovative packaging designs for consumer products. Inclusive creative environment.','Bachelor in Design/Fine Arts; Proficiency in design software; Portfolio required; Sign language support','Full-Time','2025-11-28','jobs@unilever.com.ph'),
(15,'Supply Chain Coordinator','Mid-Level','normal',3,'₱40,000 - ₱60,000','Coordinate supply chain activities, manage inventory, and optimize logistics for efficient distribution.','Bachelor degree; 2-3 years supply chain experience; Good coordination and planning skills','Full-Time','2025-12-08','jobs@unilever.com.ph'),
(15,'Sales Representative','Junior','normal',4,'₱25,000 - ₱38,000 + Incentives','Promote and sell Unilever products to retail accounts and distributors across assigned territories.','Bachelor degree; Sales experience preferred; Excellent communication; Driver license is a plus','Full-Time','2025-11-25','jobs@unilever.com.ph'),

-- PLDT Inc. (company_id: 16) - 4 jobs (1 deaf, 3 normal)
(16,'Technical Documentation Specialist','Junior','deaf',2,'₱28,000 - ₱40,000','Create technical documentation and user guides for telecom products. Deaf-friendly tech environment.','Bachelor in IT/Communications; Strong writing skills; Tech-savvy; Sign language support available','Full-Time','2025-11-30','careers@pldt.com.ph'),
(16,'Network Operations Engineer','Mid-Level','normal',3,'₱50,000 - ₱75,000','Monitor and maintain PLDT network infrastructure, troubleshoot issues, and ensure network reliability.','Bachelor in IT/Engineering; 3+ years network operations; CCNA certification preferred','Full-Time','2025-12-10','careers@pldt.com.ph'),
(16,'Business Development Manager','Senior','normal',2,'₱70,000 - ₱100,000','Identify and develop new business opportunities for PLDT enterprise solutions and services.','5+ years business development; Telecom industry knowledge; Strong negotiation skills','Full-Time','2025-12-15','careers@pldt.com.ph'),

-- Bank of the Philippine Islands (company_id: 17) - 4 jobs (1 deaf, 3 normal)
(17,'Document Processing Clerk','Entry Level','deaf',3,'₱18,000 - ₱26,000','Process banking documents and maintain accurate records. Accessible banking environment for deaf employees.','High school graduate; Detail-oriented; Basic computer skills; Sign language interpreters provided','Full-Time','2025-11-22','recruitment@bpi.com.ph'),
(17,'Branch Manager','Managerial','normal',3,'₱60,000 - ₱90,000','Lead branch operations, manage staff, achieve business targets, and ensure customer satisfaction.','5+ years banking experience; Strong leadership; Sales-driven; Bachelor in Finance/Business','Full-Time','2025-12-05','recruitment@bpi.com.ph'),
(17,'Investment Banker','Senior','normal',2,'₱90,000 - ₱140,000','Provide investment banking services, manage client portfolios, and advise on financial strategies.','7+ years investment banking; CFA or MBA preferred; Strong financial analysis skills','Full-Time','2025-12-20','recruitment@bpi.com.ph'),

-- Robinsons Retail (company_id: 18) - 4 jobs (1 deaf, 3 normal)
(18,'Inventory Clerk','Entry Level','deaf',4,'₱17,000 - ₱24,000','Track and manage store inventory using automated systems. Deaf-friendly retail workplace.','High school graduate; Organized; Basic computer skills; Sign language support available','Full-Time','2025-11-20','jobs@robinsons.com.ph'),
(18,'Category Manager','Mid-Level','normal',3,'₱45,000 - ₱65,000','Manage product categories, negotiate with suppliers, and optimize merchandise mix for profitability.','3+ years retail buying/merchandising; Strong negotiation skills; Analytical mindset','Full-Time','2025-12-01','jobs@robinsons.com.ph'),
(18,'Loss Prevention Officer','Junior','normal',4,'₱22,000 - ₱32,000','Implement loss prevention strategies, monitor store security, and investigate theft incidents.','Security/Criminology background; Observant; Good report writing; Fresh graduates welcome','Full-Time','2025-11-28','jobs@robinsons.com.ph'),

-- ABS-CBN Corporation (company_id: 19) - 4 jobs (1 deaf, 3 normal)
(19,'Video Editor','Junior','deaf',2,'₱30,000 - ₱45,000','Edit video content for various media platforms. Inclusive creative environment with deaf accommodations.','Portfolio of editing work; Proficiency in Adobe Premiere/Final Cut Pro; Creative mindset; Sign language support','Full-Time','2025-12-05','hr@abs-cbn.com'),
(19,'Content Producer','Mid-Level','normal',3,'₱50,000 - ₱75,000','Develop and produce engaging content for television, digital, and social media platforms.','3+ years content production; Creative storytelling skills; Media production knowledge','Full-Time','2025-12-12','hr@abs-cbn.com'),
(19,'Broadcast Engineer','Senior','normal',2,'₱65,000 - ₱95,000','Maintain and operate broadcast equipment, ensure signal quality, and support live productions.','Bachelor in Electronics/Communications Engineering; 5+ years broadcast experience; Technical expertise','Full-Time','2025-12-18','hr@abs-cbn.com'),

-- Petron Corporation (company_id: 20) - 4 jobs (1 deaf, 3 normal)
(20,'Laboratory Technician','Entry Level','deaf',2,'₱20,000 - ₱30,000','Conduct product quality tests in laboratory setting. Accessible workplace with visual safety systems.','Associate degree in Chemistry/Science; Laboratory skills; Detail-oriented; Sign language support','Full-Time','2025-11-25','jobs@petron.com'),
(20,'Refinery Operator','Mid-Level','normal',4,'₱40,000 - ₱60,000','Operate refinery equipment and processes to produce petroleum products safely and efficiently.','Bachelor in Chemical Engineering; 2+ years refinery experience; Safety certifications required','Full-Time','2025-12-01','jobs@petron.com'),
(20,'HSE Manager','Senior','normal',2,'₱75,000 - ₱110,000','Lead health, safety, and environmental programs across Petron operations and facilities.','7+ years HSE management; Engineering degree; ISO certifications; Strong leadership','Full-Time','2025-12-15','jobs@petron.com'),

-- De La Salle University (company_id: 21) - 4 jobs (1 deaf, 3 normal)
(21,'Library Assistant','Entry Level','deaf',2,'₱18,000 - ₱26,000','Assist in library operations, cataloging, and student services. Accessible academic environment.','Associate degree; Library science interest; Organized; Computer literate; Sign language support','Full-Time','2025-11-20','jobs@dlsu.edu.ph'),
(21,'Research Faculty','Senior','normal',2,'₱60,000 - ₱90,000','Conduct research, publish scholarly work, and teach undergraduate/graduate courses in your field.','PhD in relevant field; Published research; Teaching experience; Strong academic credentials','Full-Time','2025-12-10','jobs@dlsu.edu.ph'),
(21,'Student Affairs Officer','Mid-Level','normal',2,'₱35,000 - ₱50,000','Support student development programs, handle student concerns, and coordinate university activities.','Master degree in Student Affairs/Counseling; 3+ years experience; Strong interpersonal skills','Full-Time','2025-12-05','jobs@dlsu.edu.ph'),

-- Ateneo de Manila University (company_id: 22) - 4 jobs (1 deaf, 3 normal)
(22,'Academic Records Coordinator','Entry Level','deaf',2,'₱20,000 - ₱28,000','Maintain student records and process academic documents. Inclusive university workplace.','Bachelor degree; Detail-oriented; Computer proficient; Organized; Sign language support available','Full-Time','2025-11-22','jobs@ateneo.edu'),
(22,'Professor','Senior','normal',2,'₱70,000 - ₱100,000','Teach courses, mentor students, conduct research, and contribute to academic community.','PhD in relevant discipline; Teaching and research experience; Publications required','Full-Time','2025-12-15','jobs@ateneo.edu'),
(22,'Campus Ministry Coordinator','Mid-Level','normal',2,'₱40,000 - ₱55,000','Coordinate campus ministry programs, organize spiritual activities, and support student faith formation.','Master in Theology/Ministry; 2+ years ministry experience; Alignment with Jesuit values','Full-Time','2025-12-08','jobs@ateneo.edu'),

-- UP Manila (company_id: 23) - 4 jobs (1 deaf, 3 normal)
(23,'Medical Transcriptionist','Entry Level','deaf',2,'₱20,000 - ₱30,000 + Government Benefits','Transcribe medical records and documentation. Accessible university hospital environment.','Medical transcription training; Excellent written English; Medical terminology knowledge; Sign language support','Full-Time','2025-11-25','hr@upmc.edu.ph'),
(23,'Clinical Research Coordinator','Mid-Level','normal',2,'₱40,000 - ₱60,000 + Government Benefits','Coordinate clinical research studies, manage data, and ensure regulatory compliance.','Bachelor in Nursing/Health Sciences; 3+ years clinical research; GCP certification preferred','Full-Time','2025-12-10','hr@upmc.edu.ph'),
(23,'Medical Doctor - General Medicine','Senior','normal',3,'₱80,000 - ₱120,000 + Government Benefits','Provide general medical services at UP-PGH, participate in teaching, and support medical education.','MD degree; Licensed Physician; PRC license; Residency training completed; Teaching interest','Full-Time','2025-12-20','hr@upmc.edu.ph'),

-- Department of Education (company_id: 24) - 4 jobs (1 deaf, 3 normal)
(24,'Education Program Specialist','Entry Level','deaf',3,'₱22,000 - ₱32,000 + Government Benefits','Support implementation of education programs and prepare program reports. Accessible government workplace.','Bachelor in Education; Good analytical skills; Report writing ability; Sign language support available','Full-Time','2025-11-28','jobs@deped.gov.ph'),
(24,'School Principal','Managerial','normal',5,'₱45,000 - ₱70,000 + Government Benefits','Lead school operations, manage teachers, implement DepEd policies, and ensure quality education delivery.','Master in Education; 5+ years teaching experience; Leadership skills; DepEd eligibility required','Full-Time','2025-12-05','jobs@deped.gov.ph'),
(24,'Curriculum Development Specialist','Senior','normal',2,'₱50,000 - ₱75,000 + Government Benefits','Develop and review curriculum materials, train teachers, and improve instructional quality.','Master degree; 7+ years teaching/curriculum development; Subject matter expertise; Strong training skills','Full-Time','2025-12-15','jobs@deped.gov.ph'),

-- Bangko Sentral ng Pilipinas (company_id: 25) - 4 jobs (1 deaf, 3 normal)
(25,'Bank Records Officer','Entry Level','deaf',2,'₱25,000 - ₱35,000 + Government Benefits','Manage banking records and documentation systems. Accessible central bank workplace.','Bachelor degree; Detail-oriented; Records management skills; Computer proficient; Sign language support','Full-Time','2025-11-30','careers@bsp.gov.ph'),
(25,'Economic Research Analyst','Mid-Level','normal',3,'₱50,000 - ₱75,000 + Government Benefits','Conduct economic research, analyze monetary policy impacts, and prepare economic reports.','Master in Economics; 3+ years research experience; Strong quantitative skills; Publication record preferred','Full-Time','2025-12-10','careers@bsp.gov.ph'),
(25,'Bank Supervision Specialist','Senior','normal',2,'₱70,000 - ₱100,000 + Government Benefits','Supervise and examine banks, assess compliance with regulations, and mitigate banking risks.','CPA or Master in Finance; 5+ years banking/supervision experience; Strong analytical and regulatory knowledge','Full-Time','2025-12-20','careers@bsp.gov.ph');
COMMIT;