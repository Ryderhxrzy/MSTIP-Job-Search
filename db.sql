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
    message_to_employer TEXT DEFAULT NULL,
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

CREATE TABLE job_questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    question_text TEXT NOT NULL,
    is_required BOOLEAN DEFAULT TRUE,
    question_order INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES job_listings(job_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_job_id (job_id)
);

CREATE TABLE application_answers (
    answer_id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT,
    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (question_id) REFERENCES job_questions(question_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_application_id (application_id),
    INDEX idx_question_id (question_id)
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

-- Corrected Job Questions for All 91 Job Listings
-- Each job has exactly 10 questions with proper job_id mapping

-- Job ID 1: Software Developer (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(1,'What programming languages are you proficient in? Please list all languages and your proficiency level.', true, 1),
(1,'How many years of experience do you have in software development?', true, 2),
(1,'Describe a challenging project you worked on and how you solved the problems.', true, 3),
(1,'Are you willing to work on weekends if project deadlines require it?', true, 4),
(1,'What is your expected monthly salary for this position?', true, 5),
(1,'Do you have experience with version control systems like Git?', true, 6),
(1,'Describe your experience with database design and SQL.', true, 7),
(1,'Are you familiar with agile development methodologies?', true, 8),
(1,'What development tools and IDEs do you prefer to use?', true, 9),
(1,'Why are you interested in working at Accenture?', true, 10);

-- Job ID 2: Business Analyst (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(2,'Do you have experience with data analysis tools? Please describe your experience.', true, 1),
(2,'How would you rate your communication skills on a scale of 1-10?', true, 2),
(2,'Describe your experience with business process analysis.', true, 3),
(2,'What methodologies do you use for requirements gathering?', true, 4),
(2,'What is your expected monthly salary for this position?', true, 5),
(2,'Have you worked with stakeholders at different levels?', true, 6),
(2,'Describe a time you identified a business process improvement.', true, 7),
(2,'What experience do you have with creating business documentation?', true, 8),
(2,'Are you familiar with UML or process modeling tools?', true, 9),
(2,'Why do you want to work as a Business Analyst at Accenture?', true, 10);

-- Job ID 3: Data Scientist (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(3,'Which machine learning algorithms are you familiar with? Please provide examples.', true, 1),
(3,'Do you have experience with big data technologies? Describe your experience.', true, 2),
(3,'How many years of experience do you have in data science?', true, 3),
(3,'What programming languages do you use for data analysis?', true, 4),
(3,'What is your expected monthly salary for this position?', true, 5),
(3,'Describe a predictive model you have built and its business impact.', true, 6),
(3,'What data visualization tools are you proficient in?', true, 7),
(3,'Do you have experience with cloud platforms for data science?', true, 8),
(3,'What statistical methods do you commonly use in your work?', true, 9),
(3,'Why are you interested in this Data Scientist position at Accenture?', true, 10);

-- Job ID 4: UX/UI Designer (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(4,'Which design tools are you proficient in? Please list them.', true, 1),
(4,'Do you have a portfolio to showcase? Please provide the link.', true, 2),
(4,'How would you rate your creativity level on a scale of 1-10?', true, 3),
(4,'Describe your experience with user-centered design principles.', true, 4),
(4,'What is your expected monthly salary for this position?', true, 5),
(4,'What types of projects have you worked on (web, mobile, etc.)?', true, 6),
(4,'Do you have experience conducting user research?', true, 7),
(4,'How do you approach accessibility in your designs?', true, 8),
(4,'What design trends are you currently following?', true, 9),
(4,'Why are you interested in this UX/UI Designer role at Accenture?', true, 10);

-- Job ID 5: Project Manager (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(5,'Do you have PMP certification? If yes, please provide details.', true, 1),
(5,'How many projects have you managed throughout your career?', true, 2),
(5,'Describe your leadership style and how you handle team conflicts.', true, 3),
(5,'What project management methodologies are you experienced with?', true, 4),
(5,'What is your expected monthly salary for this position?', true, 5),
(5,'How do you handle project scope changes?', true, 6),
(5,'What tools do you use for project management?', true, 7),
(5,'Describe your experience with budget management.', true, 8),
(5,'How do you ensure project quality and deadlines are met?', true, 9),
(5,'Why do you want to work as a Project Manager at Accenture?', true, 10);

-- Job ID 6: Network Engineer (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(6,'Do you have CCNA or CCNP certification? Please provide details.', true, 1),
(6,'What network protocols are you familiar with? List them.', true, 2),
(6,'How many years of network engineering experience do you have?', true, 3),
(6,'Describe your experience with network troubleshooting.', true, 4),
(6,'What is your expected monthly salary for this position?', true, 5),
(6,'What routing and switching protocols have you worked with?', true, 6),
(6,'Do you have experience with network security?', true, 7),
(6,'What network monitoring tools have you used?', true, 8),
(6,'Describe a complex network issue you resolved.', true, 9),
(6,'Why are you interested in this Network Engineer position at Globe?', true, 10);

-- Job ID 7: Customer Service Representative (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(7,'How would you handle an angry customer? Please describe your approach.', true, 1),
(7,'Are you comfortable with chat and email communication?', true, 2),
(7,'How would you rate your customer service skills on a scale of 1-10?', true, 3),
(7,'Do you have previous customer service experience?', true, 4),
(7,'What is your expected monthly salary for this position?', true, 5),
(7,'How do you handle multiple customer inquiries simultaneously?', true, 6),
(7,'What languages do you speak fluently?', true, 7),
(7,'How do you stay calm under pressure?', true, 8),
(7,'Describe a time you went above and beyond for a customer.', true, 9),
(7,'Why are you interested in this Customer Service role at Globe?', true, 10);

-- Job ID 8: Digital Marketing Specialist (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(8,'Which digital marketing platforms have you used? Please describe.', true, 1),
(8,'Do you have Google Analytics experience? Please provide details.', true, 2),
(8,'How many years of digital marketing experience do you have?', true, 3),
(8,'What campaigns have you managed and what were the results?', true, 4),
(8,'What is your expected monthly salary for this position?', true, 5),
(8,'What social media platforms are you experienced with?', true, 6),
(8,'Do you have experience with SEO/SEM?', true, 7),
(8,'What email marketing tools have you used?', true, 8),
(8,'How do you measure campaign success?', true, 9),
(8,'Why are you interested in this Digital Marketing role at Globe?', true, 10);

-- Job ID 9: IT Security Analyst (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(9,'Do you have cybersecurity certifications? Please list them.', true, 1),
(9,'What security frameworks are you familiar with?', true, 2),
(9,'How would you rate your knowledge of cyber threats (1-10)?', true, 3),
(9,'Describe your experience with security tools and technologies.', true, 4),
(9,'What is your expected monthly salary for this position?', true, 5),
(9,'What types of security incidents have you handled?', true, 6),
(9,'Do you have experience with penetration testing?', true, 7),
(9,'What security monitoring tools are you familiar with?', true, 8),
(9,'How do you stay updated with security trends?', true, 9),
(9,'Why are you interested in this IT Security Analyst role at Globe?', true, 10);

-- Job ID 10: Retail Store Manager (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(10,'How many years of retail management experience do you have?', true, 1),
(10,'Are you comfortable with sales targets and KPIs?', true, 2),
(10,'Describe your leadership style.', true, 3),
(10,'How do you motivate your team?', true, 4),
(10,'What is your expected monthly salary for this position?', true, 5),
(10,'What experience do you have with inventory management?', true, 6),
(10,'How do you handle customer complaints?', true, 7),
(10,'What retail systems have you used?', true, 8),
(10,'Describe your experience with visual merchandising.', true, 9),
(10,'Why are you interested in this Store Manager role at Globe?', true, 10);

-- Job ID 11: Quality Assurance Tester (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(11,'Do you have software testing experience? Please describe.', true, 1),
(11,'What testing methodologies are you familiar with?', true, 2),
(11,'How would you rate your attention to detail (1-10)?', true, 3),
(11,'What testing tools have you used?', true, 4),
(11,'What is your expected monthly salary for this position?', true, 5),
(11,'Do you have experience with automated testing?', true, 6),
(11,'What types of testing have you performed?', true, 7),
(11,'How do you document and report bugs?', true, 8),
(11,'Describe a critical bug you found and how you reported it.', true, 9),
(11,'Why are you interested in this QA role at Accenture?', true, 10);

-- Job ID 12: Cloud Solutions Architect (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(12,'Which cloud platforms have you worked with? Please describe.', true, 1),
(12,'Do you have cloud certifications? Please list them.', true, 2),
(12,'How many years of cloud architecture experience do you have?', true, 3),
(12,'Describe a cloud solution you designed and implemented.', true, 4),
(12,'What is your expected monthly salary for this position?', true, 5),
(12,'What infrastructure as code tools have you used?', true, 6),
(12,'Do you have experience with cloud migration?', true, 7),
(12,'What cloud security best practices do you follow?', true, 8),
(12,'How do you optimize cloud costs?', true, 9),
(12,'Why are you interested in this Cloud Architect role at Accenture?', true, 10);

-- Job ID 13: Cybersecurity Consultant (Accenture)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(13,'What types of security assessments have you performed?', true, 1),
(13,'Do you have incident response experience? Please describe.', true, 2),
(13,'How would you rate your technical security skills (1-10)?', true, 3),
(13,'What security frameworks have you implemented?', true, 4),
(13,'What is your expected monthly salary for this position?', true, 5),
(13,'What compliance standards are you familiar with?', true, 6),
(13,'Describe your experience with security architecture.', true, 7),
(13,'What security tools do you recommend for enterprises?', true, 8),
(13,'How do you stay current with security threats?', true, 9),
(13,'Why are you interested in this Cybersecurity Consultant role at Accenture?', true, 10);

-- Job ID 14: Technical Support Specialist (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(14,'Are you comfortable with technical troubleshooting? Please describe.', true, 1),
(14,'What technical issues have you resolved? Provide examples.', true, 2),
(14,'How would you rate your problem-solving ability (1-10)?', true, 3),
(14,'What hardware and software have you supported?', true, 4),
(14,'What is your expected monthly salary for this position?', true, 5),
(14,'Do you have experience with remote support tools?', true, 6),
(14,'How do you handle difficult technical problems?', true, 7),
(14,'What documentation have you created for technical procedures?', true, 8),
(14,'Describe a complex technical issue you solved.', true, 9),
(14,'Why are you interested in this Technical Support role at Globe?', true, 10);

-- Job ID 15: Telecommunications Engineer (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(15,'What telecommunications systems have you worked with?', true, 1),
(15,'Do you have network design experience? Please describe.', true, 2),
(15,'How many years of telecom experience do you have?', true, 3),
(15,'What telecom protocols and standards are you familiar with?', true, 4),
(15,'What is your expected monthly salary for this position?', true, 5),
(15,'Do you have experience with wireless networks?', true, 6),
(15,'What network optimization techniques have you used?', true, 7),
(15,'Describe your experience with telecom equipment.', true, 8),
(15,'What network monitoring tools have you used?', true, 9),
(15,'Why are you interested in this Telecom Engineer role at Globe?', true, 10);

-- Job ID 16: Sales Executive (Globe)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(16,'Do you have sales experience? Please describe your experience.', true, 1),
(16,'What sales targets have you achieved? Provide specific examples.', true, 2),
(16,'How would you rate your communication skills (1-10)?', true, 3),
(16,'What sales techniques do you use?', true, 4),
(16,'What is your expected monthly salary for this position?', true, 5),
(16,'Do you have experience with B2B or B2C sales?', true, 6),
(16,'How do you handle sales objections?', true, 7),
(16,'What CRM systems have you used?', true, 8),
(16,'Describe your most successful sale.', true, 9),
(16,'Why are you interested in this Sales Executive role at Globe?', true, 10);

-- Job ID 17: Production Operator (SMC)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(17,'Are you comfortable with manufacturing work? Please describe.', true, 1),
(17,'Do you have safety training? Please provide details.', true, 2),
(17,'How would you rate your attention to safety (1-10)?', true, 3),
(17,'What manufacturing equipment have you operated?', true, 4),
(17,'What is your expected monthly salary for this position?', true, 5),
(17,'Do you have experience with quality control?', true, 6),
(17,'What production processes are you familiar with?', true, 7),
(17,'How do you ensure production quality?', true, 8),
(17,'Do you have experience with lean manufacturing?', true, 9),
(17,'Why are you interested in this Production Operator role at SMC?', true, 10);

-- Job ID 18: Supply Chain Analyst (SMC)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(18,'What ERP systems are you familiar with? Please describe.', true, 1),
(18,'Do you have logistics experience? Provide details.', true, 2),
(18,'How many years of supply chain experience do you have?', true, 3),
(18,'What supply chain processes have you optimized?', true, 4),
(18,'What is your expected monthly salary for this position?', true, 5),
(18,'Do you have experience with inventory management?', true, 6),
(18,'What forecasting methods have you used?', true, 7),
(18,'Describe your experience with supplier relationships.', true, 8),
(18,'What KPIs do you track in supply chain management?', true, 9),
(18,'Why are you interested in this Supply Chain Analyst role at SMC?', true, 10);

-- Job ID 19: Electrical Engineer (SMC)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(19,'Are you a licensed Electrical Engineer? Please provide license details.', true, 1),
(19,'What electrical systems have you worked with?', true, 2),
(19,'How would you rate your technical knowledge (1-10)?', true, 3),
(19,'What electrical design software have you used?', true, 4),
(19,'What is your expected monthly salary for this position?', true, 5),
(19,'Do you have experience with power systems?', true, 6),
(19,'What electrical codes and standards are you familiar with?', true, 7),
(19,'Describe your experience with project management.', true, 8),
(19,'What types of electrical projects have you completed?', true, 9),
(19,'Why are you interested in this Electrical Engineer role at SMC?', true, 10);

-- Job ID 20: Financial Analyst (SMC)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(20,'Are you proficient in Excel? Please describe your skill level.', true, 1),
(20,'What financial analysis have you performed? Provide examples.', true, 2),
(20,'How many years of financial analysis experience do you have?', true, 3),
(20,'What financial modeling techniques do you use?', true, 4),
(20,'What is your expected monthly salary for this position?', true, 5),
(20,'Do you have experience with financial reporting?', true, 6),
(20,'What accounting software have you used?', true, 7),
(20,'How do you ensure accuracy in your financial analysis?', true, 8),
(20,'Describe a complex financial analysis you completed.', true, 9),
(20,'Why are you interested in this Financial Analyst role at SMC?', true, 10);

-- Job ID 21: Graphic Designer (Ayala)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(21,'What design software do you use? Please list all.', true, 1),
(21,'Do you have a design portfolio? Please provide the link.', true, 2),
(21,'How would you rate your creativity (1-10)?', true, 3),
(21,'What types of design projects have you worked on?', true, 4),
(21,'What is your expected monthly salary for this position?', true, 5),
(21,'Do you have experience with brand design?', true, 6),
(21,'What design principles do you follow?', true, 7),
(21,'How do you handle client feedback and revisions?', true, 8),
(21,'Describe your most successful design project.', true, 9),
(21,'Why are you interested in this Graphic Designer role at Ayala?', true, 10);

-- Job ID 22: Real Estate Analyst (Ayala)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(22,'Do you have real estate analysis experience? Please describe.', true, 1),
(22,'What financial modeling have you done for real estate?', true, 2),
(22,'How many years of real estate experience do you have?', true, 3),
(22,'What property types have you analyzed?', true, 4),
(22,'What is your expected monthly salary for this position?', true, 5),
(22,'Do you have market research experience?', true, 6),
(22,'What valuation methods have you used?', true, 7),
(22,'Describe your experience with investment analysis.', true, 8),
(22,'What real estate software have you used?', true, 9),
(22,'Why are you interested in this Real Estate Analyst role at Ayala?', true, 10);

-- Job ID 23: Sustainability Manager (Ayala)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(23,'Do you have sustainability management experience? Please describe.', true, 1),
(23,'What ESG initiatives have you led?', true, 2),
(23,'How many years of sustainability experience do you have?', true, 3),
(23,'What environmental regulations are you familiar with?', true, 4),
(23,'What is your expected monthly salary for this position?', true, 5),
(23,'Do you have carbon footprint analysis experience?', true, 6),
(23,'What sustainability reporting have you done?', true, 7),
(23,'Describe your experience with stakeholder engagement.', true, 8),
(23,'What green building standards do you know?', true, 9),
(23,'Why are you interested in this Sustainability Manager role at Ayala?', true, 10);

-- Job ID 24: Investment Associate (Ayala)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(24,'Do you have investment analysis experience? Please describe.', true, 1),
(24,'What deal evaluation have you performed?', true, 2),
(24,'How many years of investment experience do you have?', true, 3),
(24,'What investment types have you worked with?', true, 4),
(24,'What is your expected monthly salary for this position?', true, 5),
(24,'Do you have financial modeling experience?', true, 6),
(24,'What due diligence have you conducted?', true, 7),
(24,'Describe your experience with portfolio management.', true, 8),
(24,'What valuation methodologies have you used?', true, 9),
(24,'Why are you interested in this Investment Associate role at Ayala?', true, 10);

-- Job ID 25: Store Crew (Jollibee)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(25,'Do you have food service experience? Please describe.', true, 1),
(25,'Are you familiar with food safety procedures?', true, 2),
(25,'How would you rate your customer service skills (1-10)?', true, 3),
(25,'What restaurant operations have you performed?', true, 4),
(25,'What is your expected monthly salary for this position?', true, 5),
(25,'Do you have cash handling experience?', true, 6),
(25,'What food preparation have you done?', true, 7),
(25,'How do you handle busy periods?', true, 8),
(25,'Describe your experience with teamwork.', true, 9),
(25,'Why are you interested in this Store Crew role at Jollibee?', true, 10);

-- Job ID 26: Operations Manager (Jollibee)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(26,'How many years of restaurant management experience do you have?', true, 1),
(26,'Do you have P&L management experience?', true, 2),
(26,'Describe your operations management approach.', true, 3),
(26,'What team size have you managed?', true, 4),
(26,'What is your expected monthly salary for this position?', true, 5),
(26,'Do you have inventory management experience?', true, 6),
(26,'What cost control measures have you implemented?', true, 7),
(26,'Describe your experience with performance metrics.', true, 8),
(26,'What process improvements have you made?', true, 9),
(26,'Why are you interested in this Operations Manager role at Jollibee?', true, 10);

-- Job ID 27: Food Technologist (Jollibee)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(27,'Do you have food technology experience? Please describe.', true, 1),
(27,'What food product development have you done?', true, 2),
(27,'How many years of food technology experience do you have?', true, 3),
(27,'What food safety standards are you familiar with?', true, 4),
(27,'What is your expected monthly salary for this position?', true, 5),
(27,'Do you have laboratory experience?', true, 6),
(27,'What quality assurance have you performed?', true, 7),
(27,'Describe your experience with sensory evaluation.', true, 8),
(27,'What food preservation techniques do you know?', true, 9),
(27,'Why are you interested in this Food Technologist role at Jollibee?', true, 10);

-- Job ID 28: Marketing Manager (Jollibee)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(28,'How many years of marketing experience do you have?', true, 1),
(28,'Do you have FMCG marketing experience?', true, 2),
(28,'Describe your marketing strategy approach.', true, 3),
(28,'What brands have you managed?', true, 4),
(28,'What is your expected monthly salary for this position?', true, 5),
(28,'Do you have digital marketing experience?', true, 6),
(28,'What campaign management have you done?', true, 7),
(28,'Describe your market research experience.', true, 8),
(28,'What brand positioning have you developed?', true, 9),
(28,'Why are you interested in this Marketing Manager role at Jollibee?', true, 10);

-- Job ID 29: Cashier (Puregold)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(29,'Do you have cashier experience? Please describe.', true, 1),
(29,'What POS systems have you used?', true, 2),
(29,'How would you rate your accuracy with numbers (1-10)?', true, 3),
(29,'What cash handling procedures are you familiar with?', true, 4),
(29,'What is your expected monthly salary for this position?', true, 5),
(29,'Do you have customer service experience?', true, 6),
(29,'What payment processing have you done?', true, 7),
(29,'How do you handle cash discrepancies?', true, 8),
(29,'Describe your experience with end-of-day procedures.', true, 9),
(29,'Why are you interested in this Cashier role at Puregold?', true, 10);

-- Job ID 30: Store Supervisor (Puregold)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(30,'How many years of retail supervision experience do you have?', true, 1),
(30,'Do you have inventory management experience?', true, 2),
(30,'Describe your supervisory style.', true, 3),
(30,'What team size have you supervised?', true, 4),
(30,'What is your expected monthly salary for this position?', true, 5),
(30,'Do you have scheduling experience?', true, 6),
(30,'What performance management have you done?', true, 7),
(30,'Describe your experience with customer service.', true, 8),
(30,'What store operations have you managed?', true, 9),
(30,'Why are you interested in this Store Supervisor role at Puregold?', true, 10);

-- Job ID 31: Procurement Officer (Puregold)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(31,'Do you have procurement experience? Please describe.', true, 1),
(31,'What purchasing have you done?', true, 2),
(31,'How many years of procurement experience do you have?', true, 3),
(31,'What procurement software have you used?', true, 4),
(31,'What is your expected monthly salary for this position?', true, 5),
(31,'Do you have supplier negotiation experience?', true, 6),
(31,'What sourcing strategies have you used?', true, 7),
(31,'Describe your experience with contract management.', true, 8),
(31,'What cost savings have you achieved?', true, 9),
(31,'Why are you interested in this Procurement Officer role at Puregold?', true, 10);

-- Job ID 32: HR Generalist (Puregold)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(32,'Do you have HR experience? Please describe.', true, 1),
(32,'What HR functions have you performed?', true, 2),
(32,'How many years of HR experience do you have?', true, 3),
(32,'What HRIS systems have you used?', true, 4),
(32,'What is your expected monthly salary for this position?', true, 5),
(32,'Do you have recruitment experience?', true, 6),
(32,'What employee relations have you handled?', true, 7),
(32,'Describe your experience with benefits administration.', true, 8),
(32,'What HR compliance have you managed?', true, 9),
(32,'Why are you interested in this HR Generalist role at Puregold?', true, 10);

-- Job ID 33: Medical Records Clerk (DOH)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(33,'Do you have medical records experience? Please describe.', true, 1),
(33,'What medical terminology do you know?', true, 2),
(33,'How would you rate your attention to detail (1-10)?', true, 3),
(33,'What records management have you done?', true, 4),
(33,'What is your expected monthly salary for this position?', true, 5),
(33,'Do you have EMR experience?', true, 6),
(33,'What confidentiality protocols have you followed?', true, 7),
(33,'Describe your experience with medical coding.', true, 8),
(33,'What record release procedures have you handled?', true, 9),
(33,'Why are you interested in this Medical Records role at DOH?', true, 10);

-- Job ID 34: Public Health Nurse (DOH)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(34,'Are you a licensed Nurse? Please provide license details.', true, 1),
(34,'Do you have public health experience? Please describe.', true, 2),
(34,'How many years of nursing experience do you have?', true, 3),
(34,'What health programs have you implemented?', true, 4),
(34,'What is your expected monthly salary for this position?', true, 5),
(34,'Do you have community health experience?', true, 6),
(34,'What health education have you conducted?', true, 7),
(34,'Describe your experience with immunization programs.', true, 8),
(34,'What disease surveillance have you done?', true, 9),
(34,'Why are you interested in this Public Health Nurse role at DOH?', true, 10);

-- Job ID 35: Health Program Officer (DOH)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(35,'Do you have health program experience? Please describe.', true, 1),
(35,'What public health programs have you managed?', true, 2),
(35,'How many years of health program experience do you have?', true, 3),
(35,'What program monitoring have you done?', true, 4),
(35,'What is your expected monthly salary for this position?', true, 5),
(35,'Do you have report writing experience?', true, 6),
(35,'What stakeholder coordination have you done?', true, 7),
(35,'Describe your experience with program evaluation.', true, 8),
(35,'What health policies have you implemented?', true, 9),
(35,'Why are you interested in this Health Program Officer role at DOH?', true, 10);

-- Job ID 36: Epidemiologist (DOH)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(36,'Do you have epidemiology experience? Please describe.', true, 1),
(36,'What disease investigation have you conducted?', true, 2),
(36,'How many years of epidemiology experience do you have?', true, 3),
(36,'What statistical analysis have you performed?', true, 4),
(36,'What is your expected monthly salary for this position?', true, 5),
(36,'Do you have research experience?', true, 6),
(36,'What surveillance systems have you used?', true, 7),
(36,'Describe your experience with outbreak investigation.', true, 8),
(36,'What data visualization have you done?', true, 9),
(36,'Why are you interested in this Epidemiologist role at DOH?', true, 10);

-- Job ID 37: Content Moderator (Concentrix)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(37,'Do you have content moderation experience? Please describe.', true, 1),
(37,'What content have you moderated?', true, 2),
(37,'How would you rate your attention to detail (1-10)?', true, 3),
(37,'What moderation guidelines have you followed?', true, 4),
(37,'What is your expected monthly salary for this position?', true, 5),
(37,'Do you have multilingual content experience?', true, 6),
(37,'What content policies have you enforced?', true, 7),
(37,'Describe your experience with user safety.', true, 8),
(37,'What moderation tools have you used?', true, 9),
(37,'Why are you interested in this Content Moderator role at Concentrix?', true, 10);

-- Job ID 38: Team Leader (Concentrix)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(38,'How many years of team leadership experience do you have?', true, 1),
(38,'Do you have BPO leadership experience?', true, 2),
(38,'Describe your team leadership approach.', true, 3),
(38,'What team size have you led?', true, 4),
(38,'What is your expected monthly salary for this position?', true, 5),
(38,'Do you have performance management experience?', true, 6),
(38,'What coaching have you provided?', true, 7),
(38,'Describe your experience with quality assurance.', true, 8),
(38,'What service level agreements have you managed?', true, 9),
(38,'Why are you interested in this Team Leader role at Concentrix?', true, 10);

-- Job ID 39: Training Specialist (Concentrix)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(39,'Do you have training experience? Please describe.', true, 1),
(39,'What training programs have you developed?', true, 2),
(39,'How many years of training experience do you have?', true, 3),
(39,'What training methodologies have you used?', true, 4),
(39,'What is your expected monthly salary for this position?', true, 5),
(39,'Do you have BPO training experience?', true, 6),
(39,'What training delivery have you done?', true, 7),
(39,'Describe your experience with needs analysis.', true, 8),
(39,'What training materials have you created?', true, 9),
(39,'Why are you interested in this Training Specialist role at Concentrix?', true, 10);

-- Job ID 40: Operations Manager (Concentrix)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(40,'How many years of BPO operations experience do you have?', true, 1),
(40,'Do you have large-scale operations experience?', true, 2),
(40,'Describe your operations management approach.', true, 3),
(40,'What operations have you overseen?', true, 4),
(40,'What is your expected monthly salary for this position?', true, 5),
(40,'Do you have SLA management experience?', true, 6),
(40,'What process improvement have you led?', true, 7),
(40,'Describe your experience with client management.', true, 8),
(40,'What operational metrics have you tracked?', true, 9),
(40,'Why are you interested in this Operations Manager role at Concentrix?', true, 10);

-- Job ID 41: Administrative Assistant (PNP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(41,'Do you have administrative experience? Please describe.', true, 1),
(41,'What office software are you proficient in?', true, 2),
(41,'How would you rate your organizational skills (1-10)?', true, 3),
(41,'What administrative tasks have you performed?', true, 4),
(41,'What is your expected monthly salary for this position?', true, 5),
(41,'Do you have government administrative experience?', true, 6),
(41,'What records management have you done?', true, 7),
(41,'Describe your experience with scheduling.', true, 8),
(41,'What communication methods have you used?', true, 9),
(41,'Why are you interested in this Administrative role at PNP?', true, 10);

-- Job ID 42: Intelligence Analyst (PNP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(42,'Do you have intelligence analysis experience? Please describe.', true, 1),
(42,'What analysis methods have you used?', true, 2),
(42,'How many years of intelligence experience do you have?', true, 3),
(42,'What intelligence tools have you worked with?', true, 4),
(42,'What is your expected monthly salary for this position?', true, 5),
(42,'Do you have law enforcement background?', true, 6),
(42,'What report writing have you done?', true, 7),
(42,'Describe your experience with data analysis.', true, 8),
(42,'What security clearance do you hold?', true, 9),
(42,'Why are you interested in this Intelligence role at PNP?', true, 10);

-- Job ID 43: IT Support Specialist (PNP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(43,'Do you have IT support experience? Please describe.', true, 1),
(43,'What hardware have you worked with?', true, 2),
(43,'How would you rate your troubleshooting skills (1-10)?', true, 3),
(43,'What operating systems are you familiar with?', true, 4),
(43,'What is your expected monthly salary for this position?', true, 5),
(43,'Do you have network support experience?', true, 6),
(43,'What help desk software have you used?', true, 7),
(43,'Describe your experience with system maintenance.', true, 8),
(43,'What certifications do you hold?', true, 9),
(43,'Why are you interested in this IT Support role at PNP?', true, 10);

-- Job ID 44: Research Assistant (DOST)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(44,'Do you have research experience? Please describe.', true, 1),
(44,'What research methods have you used?', true, 2),
(44,'How would you rate your analytical skills (1-10)?', true, 3),
(44,'What scientific fields are you interested in?', true, 4),
(44,'What is your expected monthly salary for this position?', true, 5),
(44,'Do you have data collection experience?', true, 6),
(44,'What laboratory equipment have you used?', true, 7),
(44,'Describe your experience with research reports.', true, 8),
(44,'What statistical analysis have you performed?', true, 9),
(44,'Why are you interested in this Research role at DOST?', true, 10);

-- Job ID 45: Science Research Specialist (DOST)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(45,'Do you have scientific research experience? Please describe.', true, 1),
(45,'What research projects have you led?', true, 2),
(45,'How would you rate your research skills (1-10)?', true, 3),
(45,'What scientific publications have you authored?', true, 4),
(45,'What is your expected monthly salary for this position?', true, 5),
(45,'Do you have grant writing experience?', true, 6),
(45,'What laboratory techniques are you proficient in?', true, 7),
(45,'Describe your experience with peer review.', true, 8),
(45,'What research collaborations have you participated in?', true, 9),
(45,'Why are you interested in this Research Specialist role at DOST?', true, 10);

-- Job ID 46: Technology Transfer Officer (DOST)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(46,'Do you have technology transfer experience? Please describe.', true, 1),
(46,'What technology commercialization have you done?', true, 2),
(46,'How would you rate your business development skills (1-10)?', true, 3),
(46,'What industry partnerships have you established?', true, 4),
(46,'What is your expected monthly salary for this position?', true, 5),
(46,'Do you have intellectual property knowledge?', true, 6),
(46,'What innovation scouting have you performed?', true, 7),
(46,'Describe your experience with licensing agreements.', true, 8),
(46,'What technology assessment have you conducted?', true, 9),
(46,'Why are you interested in this Technology Transfer role at DOST?', true, 10);

-- Job ID 47: Visual Merchandiser (SM)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(47,'Do you have visual merchandising experience? Please describe.', true, 1),
(47,'What retail displays have you designed?', true, 2),
(47,'How would you rate your creative skills (1-10)?', true, 3),
(47,'What design software do you use?', true, 4),
(47,'What is your expected monthly salary for this position?', true, 5),
(47,'Do you have store layout experience?', true, 6),
(47,'What seasonal displays have you created?', true, 7),
(47,'Describe your experience with visual standards.', true, 8),
(47,'What merchandising trends do you follow?', true, 9),
(47,'Why are you interested in this Visual Merchandiser role at SM?', true, 10);

-- Job ID 48: Mall Operations Officer (SM)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(48,'Do you have mall operations experience? Please describe.', true, 1),
(48,'What facility management have you done?', true, 2),
(48,'How would you rate your operational skills (1-10)?', true, 3),
(48,'What tenant coordination have you performed?', true, 4),
(48,'What is your expected monthly salary for this position?', true, 5),
(48,'Do you have mall security experience?', true, 6),
(48,'What event coordination have you handled?', true, 7),
(48,'Describe your experience with customer service.', true, 8),
(48,'What mall systems have you used?', true, 9),
(48,'Why are you interested in this Mall Operations role at SM?', true, 10);

-- Job ID 49: Property Development Associate (SM)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(49,'Do you have property development experience? Please describe.', true, 1),
(49,'What construction projects have you worked on?', true, 2),
(49,'How would you rate your project management skills (1-10)?', true, 3),
(49,'What real estate development have you done?', true, 4),
(49,'What is your expected monthly salary for this position?', true, 5),
(49,'Do you have site planning experience?', true, 6),
(49,'What development permits have you processed?', true, 7),
(49,'Describe your experience with feasibility studies.', true, 8),
(49,'What property types have you developed?', true, 9),
(49,'Why are you interested in this Property Development role at SM?', true, 10);

-- Job ID 50: Data Entry Specialist (BDO)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(50,'What is your typing speed (WPM)? Please be specific.', true, 1),
(50,'Do you have data entry experience? Please describe.', true, 2),
(50,'How would you rate your attention to detail (1-10)?', true, 3),
(50,'What data entry software have you used?', true, 4),
(50,'What is your expected monthly salary for this position?', true, 5),
(50,'Do you have banking document experience?', true, 6),
(50,'How do you ensure data accuracy?', true, 7),
(50,'What volume of data have you processed daily?', true, 8),
(50,'Describe your experience with confidential data.', true, 9),
(50,'Why are you interested in this Data Entry role at BDO?', true, 10);

-- Job ID 51: Relationship Manager (BDO)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(51,'How many years of relationship management experience do you have?', true, 1),
(51,'Do you have sales experience? Please describe.', true, 2),
(51,'Describe your client management approach.', true, 3),
(51,'What banking products have you sold?', true, 4),
(51,'What is your expected monthly salary for this position?', true, 5),
(51,'Do you have high-net-worth client experience?', true, 6),
(51,'What CRM systems have you used?', true, 7),
(51,'Describe your largest client portfolio.', true, 8),
(51,'How do you build client relationships?', true, 9),
(51,'Why are you interested in this Relationship Manager role at BDO?', true, 10);

-- Job ID 52: Credit Analyst (BDO)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(52,'What credit analysis have you performed? Please describe.', true, 1),
(52,'Do you have risk assessment experience?', true, 2),
(52,'How would you rate your financial analysis skills (1-10)?', true, 3),
(52,'What credit models have you used?', true, 4),
(52,'What is your expected monthly salary for this position?', true, 5),
(52,'Do you have loan underwriting experience?', true, 6),
(52,'What financial ratios do you analyze?', true, 7),
(52,'Describe your experience with credit reports.', true, 8),
(52,'What credit policies have you followed?', true, 9),
(52,'Why are you interested in this Credit Analyst role at BDO?', true, 10);

-- Job ID 53: Billing Analyst (Meralco)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(53,'Do you have billing experience? Please describe.', true, 1),
(53,'What billing systems have you used?', true, 2),
(53,'How would you rate your accuracy with numbers (1-10)?', true, 3),
(53,'What billing processes have you managed?', true, 4),
(53,'What is your expected monthly salary for this position?', true, 5),
(53,'Do you have customer billing inquiry experience?', true, 6),
(53,'What accounting software have you used?', true, 7),
(53,'How do you resolve billing discrepancies?', true, 8),
(53,'Describe your experience with billing reports.', true, 9),
(53,'Why are you interested in this Billing Analyst role at Meralco?', true, 10);

-- Job ID 54: Electrical Maintenance Engineer (Meralco)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(54,'What electrical systems have you maintained? Please describe.', true, 1),
(54,'Do you have utility industry experience?', true, 2),
(54,'How many years of maintenance experience do you have?', true, 3),
(54,'What electrical equipment have you serviced?', true, 4),
(54,'What is your expected monthly salary for this position?', true, 5),
(54,'Do you have preventive maintenance experience?', true, 6),
(54,'What electrical codes do you follow?', true, 7),
(54,'Describe your troubleshooting methodology.', true, 8),
(54,'What safety protocols do you implement?', true, 9),
(54,'Why are you interested in this Electrical Engineer role at Meralco?', true, 10);

-- Job ID 55: Customer Service Representative (Meralco)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(55,'Do you have customer service experience? Please describe.', true, 1),
(55,'How do you handle customer complaints?', true, 2),
(55,'How would you rate your communication skills (1-10)?', true, 3),
(55,'What service channels have you used?', true, 4),
(55,'What is your expected monthly salary for this position?', true, 5),
(55,'Do you have utility service experience?', true, 6),
(55,'How do you handle difficult customers?', true, 7),
(55,'What CRM systems have you used?', true, 8),
(55,'Describe your de-escalation techniques.', true, 9),
(55,'Why are you interested in this Customer Service role at Meralco?', true, 10);

-- Job ID 56: Production Associate (Nestlé)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(56,'Do you have manufacturing experience? Please describe.', true, 1),
(56,'Are you familiar with safety procedures?', true, 2),
(56,'How would you rate your teamwork skills (1-10)?', true, 3),
(56,'What production equipment have you operated?', true, 4),
(56,'What is your expected monthly salary for this position?', true, 5),
(56,'Do you have quality control experience?', true, 6),
(56,'What food safety practices do you follow?', true, 7),
(56,'Describe your experience with production standards.', true, 8),
(56,'What manufacturing processes have you used?', true, 9),
(56,'Why are you interested in this Production role at Nestlé?', true, 10);

-- Job ID 57: Brand Manager (Nestlé)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(57,'How many years of brand management experience do you have?', true, 1),
(57,'Do you have FMCG experience? Please describe.', true, 2),
(57,'Describe your brand strategy approach.', true, 3),
(57,'What brands have you managed?', true, 4),
(57,'What is your expected monthly salary for this position?', true, 5),
(57,'Do you have digital marketing experience?', true, 6),
(57,'What brand campaigns have you launched?', true, 7),
(57,'Describe your market research experience.', true, 8),
(57,'What brand positioning have you developed?', true, 9),
(57,'Why are you interested in this Brand Manager role at Nestlé?', true, 10);

-- Job ID 58: Quality Control Specialist (Nestlé)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(58,'What quality control have you performed? Please describe.', true, 1),
(58,'Do you have food safety knowledge?', true, 2),
(58,'How would you rate your attention to detail (1-10)?', true, 3),
(58,'What testing methods have you used?', true, 4),
(58,'What is your expected monthly salary for this position?', true, 5),
(58,'Do you have HACCP experience?', true, 6),
(58,'What quality standards are you familiar with?', true, 7),
(58,'Describe your experience with quality audits.', true, 8),
(58,'What laboratory equipment have you used?', true, 9),
(58,'Why are you interested in this Quality Control role at Nestlé?', true, 10);

-- Job ID 59: Packaging Designer (Unilever)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(59,'Do you have packaging design experience? Please describe.', true, 1),
(59,'What design software do you use?', true, 2),
(59,'How would you rate your creative skills (1-10)?', true, 3),
(59,'What packaging have you designed?', true, 4),
(59,'What is your expected monthly salary for this position?', true, 5),
(59,'Do you have consumer product design experience?', true, 6),
(59,'What printing processes are you familiar with?', true, 7),
(59,'Describe your experience with packaging materials.', true, 8),
(59,'What design trends do you follow?', true, 9),
(59,'Why are you interested in this Packaging Designer role at Unilever?', true, 10);

-- Job ID 60: Supply Chain Coordinator (Unilever)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(60,'What supply chain functions have you handled? Please describe.', true, 1),
(60,'Do you have inventory management experience?', true, 2),
(60,'How many years of supply chain experience do you have?', true, 3),
(60,'What logistics have you coordinated?', true, 4),
(60,'What is your expected monthly salary for this position?', true, 5),
(60,'Do you have warehouse experience?', true, 6),
(60,'What ERP systems have you used?', true, 7),
(60,'Describe your experience with demand planning.', true, 8),
(60,'What supply chain KPIs do you track?', true, 9),
(60,'Why are you interested in this Supply Chain role at Unilever?', true, 10);

-- Job ID 61: Sales Representative (Unilever)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(61,'Do you have sales experience? Please describe.', true, 1),
(61,'What sales targets have you achieved?', true, 2),
(61,'How would you rate your persuasion skills (1-10)?', true, 3),
(61,'What sales channels have you used?', true, 4),
(61,'What is your expected monthly salary for this position?', true, 5),
(61,'Do you have retail account experience?', true, 6),
(61,'What sales techniques do you use?', true, 7),
(61,'Describe your negotiation experience.', true, 8),
(61,'What CRM systems have you used?', true, 9),
(61,'Why are you interested in this Sales role at Unilever?', true, 10);

-- Job ID 62: Technical Documentation Specialist (PLDT)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(62,'Do you have technical writing experience? Please describe.', true, 1),
(62,'What documentation have you created?', true, 2),
(62,'How would you rate your writing skills (1-10)?', true, 3),
(62,'What technical products have you documented?', true, 4),
(62,'What is your expected monthly salary for this position?', true, 5),
(62,'Do you have telecom product knowledge?', true, 6),
(62,'What documentation tools have you used?', true, 7),
(62,'Describe your experience with user guides.', true, 8),
(62,'What API documentation have you written?', true, 9),
(62,'Why are you interested in this Technical Documentation role at PLDT?', true, 10);

-- Job ID 63: Network Operations Engineer (PLDT)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(63,'What network operations have you performed? Please describe.', true, 1),
(63,'Do you have CCNA certification?', true, 2),
(63,'How many years of network operations experience do you have?', true, 3),
(63,'What network monitoring tools have you used?', true, 4),
(63,'What is your expected monthly salary for this position?', true, 5),
(63,'Do you have incident response experience?', true, 6),
(63,'What network protocols have you worked with?', true, 7),
(63,'Describe your experience with network troubleshooting.', true, 8),
(63,'What network performance metrics do you monitor?', true, 9),
(63,'Why are you interested in this Network Operations role at PLDT?', true, 10);

-- Job ID 64: Business Development Manager (PLDT)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(64,'How many years of business development experience do you have?', true, 1),
(64,'Do you have telecom industry knowledge?', true, 2),
(64,'Describe your business development approach.', true, 3),
(64,'What business opportunities have you developed?', true, 4),
(64,'What is your expected monthly salary for this position?', true, 5),
(64,'Do you have enterprise sales experience?', true, 6),
(64,'What strategic partnerships have you built?', true, 7),
(64,'Describe your market analysis experience.', true, 8),
(64,'What revenue growth have you achieved?', true, 9),
(64,'Why are you interested in this Business Development role at PLDT?', true, 10);

-- Job ID 65: Document Processing Clerk (BPI)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(65,'Do you have document processing experience? Please describe.', true, 1),
(65,'What banking documents have you handled?', true, 2),
(65,'How would you rate your organizational skills (1-10)?', true, 3),
(65,'What document management systems have you used?', true, 4),
(65,'What is your expected monthly salary for this position?', true, 5),
(65,'Do you have compliance documentation experience?', true, 6),
(65,'What scanning equipment have you operated?', true, 7),
(65,'Describe your experience with file management.', true, 8),
(65,'What data entry have you performed?', true, 9),
(65,'Why are you interested in this Document Processing role at BPI?', true, 10);

-- Job ID 66: Branch Manager (BPI)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(66,'How many years of branch management experience do you have?', true, 1),
(66,'Do you have sales leadership experience?', true, 2),
(66,'Describe your management approach.', true, 3),
(66,'What banking operations have you managed?', true, 4),
(66,'What is your expected monthly salary for this position?', true, 5),
(66,'Do you have P&L responsibility experience?', true, 6),
(66,'What team size have you managed?', true, 7),
(66,'Describe your experience with branch targets.', true, 8),
(66,'What customer service standards have you implemented?', true, 9),
(66,'Why are you interested in this Branch Manager role at BPI?', true, 10);

-- Job ID 67: Investment Banker (BPI)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(67,'Do you have investment banking experience? Please describe.', true, 1),
(67,'What financial products have you worked with?', true, 2),
(67,'How would you rate your financial analysis skills (1-10)?', true, 3),
(67,'What deals have you structured?', true, 4),
(67,'What is your expected monthly salary for this position?', true, 5),
(67,'Do you have M&A experience?', true, 6),
(67,'What valuation methods have you used?', true, 7),
(67,'Describe your experience with client portfolios.', true, 8),
(67,'What financial modeling have you done?', true, 9),
(67,'Why are you interested in this Investment Banking role at BPI?', true, 10);

-- Job ID 68: Inventory Clerk (Robinsons)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(68,'Do you have inventory management experience? Please describe.', true, 1),
(68,'What inventory systems have you used?', true, 2),
(68,'How would you rate your attention to detail (1-10)?', true, 3),
(68,'What stock control have you performed?', true, 4),
(68,'What is your expected monthly salary for this position?', true, 5),
(68,'Do you have retail inventory experience?', true, 6),
(68,'What cycle counting have you done?', true, 7),
(68,'Describe your experience with stock reconciliation.', true, 8),
(68,'What inventory reports have you prepared?', true, 9),
(68,'Why are you interested in this Inventory Clerk role at Robinsons?', true, 10);

-- Job ID 69: Category Manager (Robinsons)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(69,'Do you have category management experience? Please describe.', true, 1),
(69,'What product categories have you managed?', true, 2),
(69,'How would you rate your negotiation skills (1-10)?', true, 3),
(69,'What supplier relationships have you built?', true, 4),
(69,'What is your expected monthly salary for this position?', true, 5),
(69,'Do you have retail buying experience?', true, 6),
(69,'What merchandising strategies have you used?', true, 7),
(69,'Describe your experience with category performance.', true, 8),
(69,'What market analysis have you conducted?', true, 9),
(69,'Why are you interested in this Category Manager role at Robinsons?', true, 10);

-- Job ID 70: Loss Prevention Officer (Robinsons)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(70,'Do you have loss prevention experience? Please describe.', true, 1),
(70,'What security measures have you implemented?', true, 2),
(70,'How would you rate your observation skills (1-10)?', true, 3),
(70,'What theft investigations have you conducted?', true, 4),
(70,'What is your expected monthly salary for this position?', true, 5),
(70,'Do you have retail security experience?', true, 6),
(70,'What surveillance systems have you used?', true, 7),
(70,'Describe your experience with incident reports.', true, 8),
(70,'What loss prevention strategies have you developed?', true, 9),
(70,'Why are you interested in this Loss Prevention role at Robinsons?', true, 10);

-- Job ID 71: Video Editor (ABS-CBN)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(71,'Do you have video editing experience? Please describe.', true, 1),
(71,'What video editing software are you proficient in?', true, 2),
(71,'How would you rate your creative skills (1-10)?', true, 3),
(71,'What types of videos have you edited?', true, 4),
(71,'What is your expected monthly salary for this position?', true, 5),
(71,'Do you have experience with motion graphics?', true, 6),
(71,'What video formats are you familiar with?', true, 7),
(71,'Describe your experience with post-production.', true, 8),
(71,'What projects are you most proud of?', true, 9),
(71,'Why are you interested in this Video Editor role at ABS-CBN?', true, 10);

-- Job ID 72: Content Producer (ABS-CBN)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(72,'Do you have content production experience? Please describe.', true, 1),
(72,'What media have you produced content for?', true, 2),
(72,'How would you rate your storytelling skills (1-10)?', true, 3),
(72,'What production equipment have you used?', true, 4),
(72,'What is your expected monthly salary for this position?', true, 5),
(72,'Do you have live production experience?', true, 6),
(72,'What content management systems have you used?', true, 7),
(72,'Describe your experience with content strategy.', true, 8),
(72,'What audiences have you created content for?', true, 9),
(72,'Why are you interested in this Content Producer role at ABS-CBN?', true, 10);

-- Job ID 73: Broadcast Engineer (ABS-CBN)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(73,'Do you have broadcast engineering experience? Please describe.', true, 1),
(73,'What broadcast equipment have you worked with?', true, 2),
(73,'How would you rate your technical skills (1-10)?', true, 3),
(73,'What transmission systems have you maintained?', true, 4),
(73,'What is your expected monthly salary for this position?', true, 5),
(73,'Do you have live broadcast experience?', true, 6),
(73,'What signal processing have you done?', true, 7),
(73,'Describe your experience with studio equipment.', true, 8),
(73,'What broadcast standards are you familiar with?', true, 9),
(73,'Why are you interested in this Broadcast Engineer role at ABS-CBN?', true, 10);

-- Job ID 74: Laboratory Technician (Petron)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(74,'Do you have laboratory experience? Please describe.', true, 1),
(74,'What laboratory equipment have you used?', true, 2),
(74,'How would you rate your technical skills (1-10)?', true, 3),
(74,'What testing methods have you performed?', true, 4),
(74,'What is your expected monthly salary for this position?', true, 5),
(74,'Do you have quality control experience?', true, 6),
(74,'What safety protocols have you followed?', true, 7),
(74,'Describe your experience with sample preparation.', true, 8),
(74,'What analysis techniques have you used?', true, 9),
(74,'Why are you interested in this Laboratory Technician role at Petron?', true, 10);

-- Job ID 75: Refinery Operator (Petron)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(75,'Do you have refinery operations experience? Please describe.', true, 1),
(75,'What refinery equipment have you operated?', true, 2),
(75,'How would you rate your technical skills (1-10)?', true, 3),
(75,'What processes have you managed?', true, 4),
(75,'What is your expected monthly salary for this position?', true, 5),
(75,'Do you have safety certifications?', true, 6),
(75,'What control systems have you used?', true, 7),
(75,'Describe your experience with process monitoring.', true, 8),
(75,'What emergency procedures have you followed?', true, 9),
(75,'Why are you interested in this Refinery Operator role at Petron?', true, 10);

-- Job ID 76: HSE Manager (Petron)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(76,'Do you have HSE management experience? Please describe.', true, 1),
(76,'What safety programs have you implemented?', true, 2),
(76,'How would you rate your leadership skills (1-10)?', true, 3),
(76,'What environmental compliance have you managed?', true, 4),
(76,'What is your expected monthly salary for this position?', true, 5),
(76,'Do you have ISO certification experience?', true, 6),
(76,'What risk assessments have you conducted?', true, 7),
(76,'Describe your experience with safety audits.', true, 8),
(76,'What HSE training have you delivered?', true, 9),
(76,'Why are you interested in this HSE Manager role at Petron?', true, 10);

-- Job ID 77: Library Assistant (DLSU)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(77,'Do you have library experience? Please describe.', true, 1),
(77,'What library systems have you used?', true, 2),
(77,'How would you rate your organizational skills (1-10)?', true, 3),
(77,'What cataloging have you performed?', true, 4),
(77,'What is your expected monthly salary for this position?', true, 5),
(77,'Do you have customer service experience?', true, 6),
(77,'What research assistance have you provided?', true, 7),
(77,'Describe your experience with library management.', true, 8),
(77,'What digital resources have you managed?', true, 9),
(77,'Why are you interested in this Library Assistant role at DLSU?', true, 10);

-- Job ID 78: Research Faculty (DLSU)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(78,'Do you have research experience? Please describe.', true, 1),
(78,'What research have you published?', true, 2),
(78,'How would you rate your academic skills (1-10)?', true, 3),
(78,'What subjects have you taught?', true, 4),
(78,'What is your expected monthly salary for this position?', true, 5),
(78,'Do you have grant writing experience?', true, 6),
(78,'What academic conferences have you attended?', true, 7),
(78,'Describe your experience with student mentoring.', true, 8),
(78,'What research methodologies have you used?', true, 9),
(78,'Why are you interested in this Research Faculty role at DLSU?', true, 10);

-- Job ID 79: Student Affairs Officer (DLSU)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(79,'Do you have student affairs experience? Please describe.', true, 1),
(79,'What student programs have you managed?', true, 2),
(79,'How would you rate your counseling skills (1-10)?', true, 3),
(79,'What student issues have you handled?', true, 4),
(79,'What is your expected monthly salary for this position?', true, 5),
(79,'Do you have event coordination experience?', true, 6),
(79,'What student organizations have you advised?', true, 7),
(79,'Describe your experience with student development.', true, 8),
(79,'What campus activities have you organized?', true, 9),
(79,'Why are you interested in this Student Affairs role at DLSU?', true, 10);

-- Job ID 80: Academic Records Coordinator (Ateneo)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(80,'Do you have academic records experience? Please describe.', true, 1),
(80,'What student information systems have you used?', true, 2),
(80,'How would you rate your attention to detail (1-10)?', true, 3),
(80,'What academic documents have you processed?', true, 4),
(80,'What is your expected monthly salary for this position?', true, 5),
(80,'Do you have transcript evaluation experience?', true, 6),
(80,'What registrar functions have you performed?', true, 7),
(80,'Describe your experience with student data.', true, 8),
(80,'What confidentiality protocols have you followed?', true, 9),
(80,'Why are you interested in this Academic Records role at Ateneo?', true, 10);

-- Job ID 81: Professor (Ateneo)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(81,'How many years of teaching experience do you have?', true, 1),
(81,'Do you have published research? Please provide details.', true, 2),
(81,'Describe your teaching philosophy.', true, 3),
(81,'What subjects are you qualified to teach?', true, 4),
(81,'What is your expected monthly salary for this position?', true, 5),
(81,'What is your highest academic degree?', true, 6),
(81,'Do you have experience with curriculum development?', true, 7),
(81,'What teaching methods do you use?', true, 8),
(81,'Describe your research interests.', true, 9),
(81,'Why are you interested in teaching at Ateneo?', true, 10);

-- Job ID 82: Campus Ministry Coordinator (Ateneo)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(82,'Do you have campus ministry experience? Please describe.', true, 1),
(82,'What ministry programs have you coordinated?', true, 2),
(82,'How would you rate your spiritual leadership skills (1-10)?', true, 3),
(82,'What faith formation activities have you organized?', true, 4),
(82,'What is your expected monthly salary for this position?', true, 5),
(82,'Do you have Jesuit education background?', true, 6),
(82,'What retreat programs have you facilitated?', true, 7),
(82,'Describe your experience with student spiritual development.', true, 8),
(82,'What liturgical celebrations have you coordinated?', true, 9),
(82,'Why are you interested in this Campus Ministry role at Ateneo?', true, 10);

-- Job ID 83: Medical Transcriptionist (UP Manila)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(83,'Do you have medical transcription experience? Please describe.', true, 1),
(83,'What medical specialties have you transcribed?', true, 2),
(83,'How would you rate your typing accuracy (1-10)?', true, 3),
(83,'What transcription equipment have you used?', true, 4),
(83,'What is your expected monthly salary for this position?', true, 5),
(83,'Do you have medical terminology knowledge?', true, 6),
(83,'How do you ensure transcription accuracy?', true, 7),
(83,'What quality control methods do you use?', true, 8),
(83,'Describe your experience with HIPAA compliance.', true, 9),
(83,'Why are you interested in this Medical Transcriptionist role at UP Manila?', true, 10);

-- Job ID 84: Clinical Research Coordinator (UP Manila)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(84,'Do you have clinical research experience? Please describe.', true, 1),
(84,'What clinical studies have you coordinated?', true, 2),
(84,'How many years of clinical research experience do you have?', true, 3),
(84,'Do you have GCP certification?', true, 4),
(84,'What is your expected monthly salary for this position?', true, 5),
(84,'What clinical protocols have you worked with?', true, 6),
(84,'Do you have IRB submission experience?', true, 7),
(84,'What data management systems have you used?', true, 8),
(84,'Describe your patient recruitment experience.', true, 9),
(84,'Why are you interested in this Clinical Research role at UP Manila?', true, 10);

-- Job ID 85: Medical Doctor - General Medicine (UP Manila)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(85,'Are you a licensed Medical Doctor? Please provide license details.', true, 1),
(85,'What medical specialties have you practiced?', true, 2),
(85,'How would you rate your clinical skills (1-10)?', true, 3),
(85,'Do you have teaching experience?', true, 4),
(85,'What is your expected monthly salary for this position?', true, 5),
(85,'What clinical areas are you most experienced in?', true, 6),
(85,'Do you have emergency medicine experience?', true, 7),
(85,'What research have you conducted?', true, 8),
(85,'Describe your approach to patient care.', true, 9),
(85,'Why are you interested in working at UP-PGH?', true, 10);

-- Job ID 86: Education Program Specialist (DepEd)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(86,'Do you have education program experience? Please describe.', true, 1),
(86,'What education programs have you implemented?', true, 2),
(86,'How many years of education experience do you have?', true, 3),
(86,'Do you have report writing experience?', true, 4),
(86,'What is your expected monthly salary for this position?', true, 5),
(86,'What educational policies are you familiar with?', true, 6),
(86,'Do you have curriculum development experience?', true, 7),
(86,'What data analysis have you performed for education programs?', true, 8),
(86,'Describe your experience with stakeholder coordination.', true, 9),
(86,'Why are you interested in working at DepEd?', true, 10);

-- Job ID 87: School Principal (DepEd)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(87,'How many years of teaching experience do you have?', true, 1),
(87,'Do you have administrative experience? Please describe.', true, 2),
(87,'Describe your leadership approach.', true, 3),
(87,'What school management experience do you have?', true, 4),
(87,'What is your expected monthly salary for this position?', true, 5),
(87,'Do you have DepEd eligibility?', true, 6),
(87,'What educational innovations have you implemented?', true, 7),
(87,'How do you handle teacher performance issues?', true, 8),
(87,'Describe your experience with community engagement.', true, 9),
(87,'Why are you interested in this School Principal role at DepEd?', true, 10);

-- Job ID 88: Curriculum Development Specialist (DepEd)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(88,'Do you have curriculum development experience? Please describe.', true, 1),
(88,'What curriculum have you developed?', true, 2),
(88,'How many years of curriculum experience do you have?', true, 3),
(88,'What subject areas are you expert in?', true, 4),
(88,'What is your expected monthly salary for this position?', true, 5),
(88,'Do you have teacher training experience?', true, 6),
(88,'What assessment methods have you developed?', true, 7),
(88,'Describe your experience with educational standards.', true, 8),
(88,'What curriculum evaluation have you conducted?', true, 9),
(88,'Why are you interested in this Curriculum role at DepEd?', true, 10);

-- Job ID 89: Bank Records Officer (BSP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(89,'Do you have records management experience? Please describe.', true, 1),
(89,'What banking records have you managed?', true, 2),
(89,'How would you rate your organizational skills (1-10)?', true, 3),
(89,'What records management systems have you used?', true, 4),
(89,'What is your expected monthly salary for this position?', true, 5),
(89,'Do you have compliance documentation experience?', true, 6),
(89,'What data privacy regulations are you familiar with?', true, 7),
(89,'Describe your experience with audit preparation.', true, 8),
(89,'What document imaging systems have you used?', true, 9),
(89,'Why are you interested in this Records Officer role at BSP?', true, 10);

-- Job ID 90: Economic Research Analyst (BSP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(90,'Do you have economic research experience? Please describe.', true, 1),
(90,'What economic analysis have you performed?', true, 2),
(90,'How many years of economic research experience do you have?', true, 3),
(90,'What economic models have you used?', true, 4),
(90,'What is your expected monthly salary for this position?', true, 5),
(90,'Do you have monetary policy analysis experience?', true, 6),
(90,'What statistical software are you proficient in?', true, 7),
(90,'Describe your experience with economic forecasting.', true, 8),
(90,'What research publications have you contributed to?', true, 9),
(90,'Why are you interested in this Economic Research role at BSP?', true, 10);

-- Job ID 91: Bank Supervision Specialist (BSP)
INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES 
(91,'Do you have bank supervision experience? Please describe.', true, 1),
(91,'What banking regulations are you familiar with?', true, 2),
(91,'How many years of banking supervision experience do you have?', true, 3),
(91,'What types of banks have you supervised?', true, 4),
(91,'What is your expected monthly salary for this position?', true, 5),
(91,'Do you have risk assessment experience?', true, 6),
(91,'What supervisory frameworks have you used?', true, 7),
(91,'Describe your experience with compliance examinations.', true, 8),
(91,'What supervisory reports have you prepared?', true, 9),
(91,'Why are you interested in this Bank Supervision role at BSP?', true, 10);
