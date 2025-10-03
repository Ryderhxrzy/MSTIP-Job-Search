-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2025 at 03:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mstip_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Reviewed','Accepted','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `company_type` enum('Government','Private') NOT NULL,
  `government_agency` varchar(150) DEFAULT NULL,
  `location` varchar(150) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `industry` varchar(150) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `company_size` varchar(50) DEFAULT NULL,
  `founded_year` int(11) DEFAULT NULL,
  `company_culture` text DEFAULT NULL,
  `work_environment` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `about_company` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `user_id`, `company_name`, `company_type`, `government_agency`, `location`, `website`, `industry`, `contact_number`, `email_address`, `company_size`, `founded_year`, `company_culture`, `work_environment`, `benefits`, `about_company`, `profile_picture`, `cover_image`, `created_at`, `updated_at`) VALUES
(1, 'E100001', 'Accenture Philippines', 'Private', NULL, 'Quezon City, Metro Manila', 'https://www.accenture.com/ph-en', 'Information Technology and Consulting', '(02) 555-1234', 'careers.ph@accenture.com', '50,000+', 1989, 'Innovation-driven, diverse, inclusive', 'Fast-paced, collaborative, technology-focused', 'Health insurance, training, remote work options', 'Accenture is a global professional services company with leading capabilities in digital, cloud and security. Combining unmatched experience and specialized skills across more than 40 industries, we offer Strategy and Consulting, Interactive, Technology and Operations services. Our people deliver on the promise of technology and human ingenuity every day, helping clients in the Philippines and around the world to become more competitive and future-ready.', 'images/accenture_logo.jpg', 'images/accenture_cover.png', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(2, 'E100002', 'Globe Telecom', 'Private', NULL, 'Taguig City, Metro Manila', 'https://www.globe.com.ph', 'Telecommunications', '(02) 730-1000', 'info@globe.com.ph', '8,000+', 1935, 'Customer-focused, innovative, agile', 'Dynamic, inclusive, growth-oriented', 'Telco perks, health benefits, employee discounts', 'Globe Telecom is a major provider of telecommunications services in the Philippines. We empower individuals, businesses, and institutions through our mobile, fixed line, broadband, and enterprise solutions. We are committed to creating wonderful digital experiences that enrich the lives of Filipinos, supporting innovation and inclusivity in a rapidly evolving digital world.', 'images/globe_logo.png', 'images/globe_cover.png', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(3, 'E100003', 'San Miguel Corporation', 'Private', NULL, 'Mandaluyong City, Metro Manila', 'https://www.sanmiguel.com.ph', 'Conglomerate (Food, Beverage, Infrastructure, Energy)', '(02) 8632-3000', 'info@sanmiguel.com.ph', '25,000+', 1890, 'Legacy-driven, diverse industries, nation-building', 'Corporate, structured, large-scale operations', 'Comprehensive health, retirement, product discounts', 'San Miguel Corporation is one of the Philippines’ largest and most diversified conglomerates with operations in beverages, food, packaging, fuel and oil, power, infrastructure, and property. With over a century of history, SMC has built trusted household brands and continues to play a vital role in national development while maintaining its mission of delivering quality products and services to millions of Filipinos.', 'images/smc_logo.png', 'images/smc_cover.png', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(4, 'E100004', 'Ayala Corporation', 'Private', NULL, 'Makati City, Metro Manila', 'https://www.ayala.com.ph', 'Conglomerate (Real Estate, Banking, Telecom, Power)', '(02) 7730-1000', 'info@ayala.com.ph', '35,000+', 1834, 'Sustainability, innovation, leadership', 'Corporate, strategic, collaborative', 'Health insurance, scholarships, stock options', 'Ayala Corporation is one of the oldest and most respected conglomerates in the Philippines. With businesses spanning real estate, banking, telecommunications, water, power, healthcare, and education, Ayala has consistently been at the forefront of innovation and nation-building. The company is committed to sustainability and inclusive growth, creating long-term value for stakeholders and the communities it serves.', 'images/ayala_logo.webp', 'images/ayala_cover.png', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(5, 'E100005', 'Jollibee Foods Corporation', 'Private', NULL, 'Pasig City, Metro Manila', 'https://www.jollibee.com.ph', 'Food and Beverage (Restaurant Chain)', '(02) 8634-1111', 'info@jfc.com.ph', '15,000+', 1978, 'Family-oriented, Filipino values, customer-first', 'Friendly, service-oriented, growth-focused', 'Meal discounts, health coverage, training programs', 'Jollibee Foods Corporation (JFC) is the largest Asian food service company, operating over 6,000 stores worldwide across multiple brands including Jollibee, Chowking, Greenwich, Red Ribbon, Mang Inasal, and Burger King Philippines. Known for its iconic Chickenjoy and strong Filipino heritage, JFC is committed to serving great tasting food and bringing the joy of eating to everyone, everywhere.', 'images/jollibee_logo.png', 'images/jollibee_cover.jpg', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(6, 'E100006', 'Puregold Price Club', 'Private', NULL, 'Quezon City, Metro Manila', 'https://www.puregold.com.ph', 'Retail and Supermarket', '(02) 8912-8888', 'customerservice@puregold.com.ph', '5,000+', 1998, 'Value-driven, community-focused', 'Retail-oriented, customer-first, fast-paced', 'Employee discounts, health plans, bonuses', 'Puregold Price Club, Inc. is one of the leading supermarket chains in the Philippines. Established to serve the everyday needs of Filipino families, Puregold provides a wide range of affordable groceries and household essentials. With its rapidly expanding footprint nationwide, the company has become a trusted retail brand known for value, convenience, and quality service.', 'images/puregold_logo.webp', 'images/puregold_cover.png', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(7, 'E100007', 'Department of Health', 'Government', 'DOH', 'Manila, Philippines', 'https://www.doh.gov.ph', 'Government (Healthcare Services)', '(02) 8651-7800', 'info@doh.gov.ph', '10,000+', 1898, 'Public service, health-focused', 'Government office, community service', 'Government health benefits, retirement, allowances', 'The Department of Health (DOH) is the principal health agency of the Philippine government. It is responsible for ensuring access to basic public health services, leading programs on disease prevention, health promotion, and regulation of health services. The DOH works tirelessly to improve healthcare systems, strengthen public hospitals, and respond to health emergencies nationwide.', 'images/doh_logo.svg', 'images/doh_cover.avif', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(8, 'E100008', 'Concentrix Philippines', 'Private', NULL, 'Quezon City, Metro Manila', 'https://www.concentrix.com', 'Business Process Outsourcing (BPO)', '(02) 7777-7777', 'careers@concentrix.com', '80,000+', 2007, 'Customer-focused, people-first, global mindset', 'BPO, energetic, supportive teams', 'HMO coverage, allowances, career development', 'Concentrix is a leading global provider of customer experience (CX) solutions and technology. In the Philippines, Concentrix is one of the largest BPO employers, offering services in customer care, technical support, sales, and back-office operations across multiple industries. We focus on creating exceptional experiences for our clients and providing growth opportunities for our employees.', 'images/concentrix_logo.jpg', 'images/concentrix_cover.jpg', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(9, 'E100009', 'Philippine National Police', 'Government', 'PNP', 'Quezon City, Metro Manila', 'https://www.pnp.gov.ph', 'Government (Law Enforcement and Public Safety)', '(02) 8723-0401', 'info@pnp.gov.ph', '220,000+', 1991, 'Service, honor, justice', 'Law enforcement, field and office work', 'Government benefits, hazard pay, retirement', 'The Philippine National Police (PNP) is the armed national police force tasked with enforcing the law, preventing and controlling crimes, and maintaining peace and order across the country. Guided by its vision to be a highly capable, effective, and credible police service, the PNP continues to safeguard communities and uphold the rule of law in service to the Filipino people.', 'images/pnp_logo.png', 'images/pnp_cover.jpg', '2025-10-01 09:28:16', '2025-10-03 01:41:31'),
(10, 'E100010', 'Department of Science and Technology', 'Government', 'DOST', 'Taguig City, Metro Manila', 'https://www.dost.gov.ph', 'Government (Science and Technology)', '(02) 8837-2071', 'info@dost.gov.ph', '5,000+', 1958, 'Research-driven, innovation-focused', 'Government office, scientific research', 'Research grants, government benefits, training', 'The Department of Science and Technology (DOST) leads the Philippine government’s initiatives to harness science, technology, and innovation for national progress. It provides scientific and technological services, supports research and development, and drives programs that enhance industry competitiveness. The DOST envisions a technologically advanced nation that is sustainable, resilient, and inclusive.', 'images/dost_logo.png', 'images/dost_cover.jpg', '2025-10-01 09:28:16', '2025-10-03 01:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `graduate_information`
--

CREATE TABLE `graduate_information` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `course` varchar(200) NOT NULL,
  `year_graduated` int(11) NOT NULL,
  `skills` text DEFAULT NULL,
  `resume` varchar(255) NOT NULL,
  `linkedin_profile` text DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_listings`
--

CREATE TABLE `job_listings` (
  `job_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `job_position` enum('Entry Level','Junior','Mid-Level','Senior','Managerial') DEFAULT 'Entry Level',
  `job_category` enum('normal','deaf') DEFAULT 'normal',
  `slots_available` int(11) NOT NULL DEFAULT 1,
  `salary_range` varchar(100) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `job_type_shift` enum('Full-Time','Part-Time') DEFAULT 'Full-Time',
  `application_deadline` date DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `posted_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Open','Closed') DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_listings`
--

INSERT INTO `job_listings` (`job_id`, `company_id`, `job_title`, `job_position`, `job_category`, `slots_available`, `salary_range`, `job_description`, `qualifications`, `job_type_shift`, `application_deadline`, `contact_email`, `image_url`, `posted_date`, `status`) VALUES
(1, 1, 'Software Developer', 'Junior', 'normal', 5, '₱35,000 - ₱50,000', 'We are looking for passionate Software Developers to join our development team. You will be responsible for designing, coding, and modifying software applications according to client specifications.', 'Bachelor\'s degree in Computer Science or related field; Knowledge of programming languages such as Java, Python, or C#; Strong problem-solving skills', 'Full-Time', '2025-11-30', 'careers@accenture.com', NULL, '2025-10-02 01:45:19', 'Open'),
(2, 1, 'Business Analyst', 'Mid-Level', 'normal', 3, '₱50,000 - ₱70,000', 'Join our team as a Business Analyst where you will bridge the gap between IT and business. You will analyze business processes and recommend technology solutions.', '3+ years experience in business analysis; Excellent communication skills; Proficiency in data analysis tools; Bachelor\'s degree in Business or IT', 'Full-Time', '2025-11-30', 'careers@accenture.com', NULL, '2025-10-02 01:45:19', 'Open'),
(3, 1, 'Data Scientist', 'Senior', 'normal', 2, '₱80,000 - ₱120,000', 'We need an experienced Data Scientist to analyze large datasets and develop predictive models that drive business decisions.', 'Master\'s degree in Data Science or related field; 5+ years experience; Proficiency in Python, R, and SQL; Experience with machine learning algorithms', 'Full-Time', '2025-12-15', 'careers@accenture.com', NULL, '2025-10-02 01:45:19', 'Open'),
(4, 1, 'UX/UI Designer', 'Junior', 'deaf', 3, '₱30,000 - ₱45,000', 'Design intuitive and engaging user interfaces for web and mobile applications. This position welcomes deaf and hard-of-hearing candidates with full accessibility support.', 'Portfolio showcasing design work; Proficiency in Figma, Adobe XD, or Sketch; Understanding of user-centered design principles; Sign language support available', 'Full-Time', '2025-11-25', 'careers@accenture.com', NULL, '2025-10-02 01:45:19', 'Open'),
(5, 1, 'Project Manager', 'Managerial', 'normal', 2, '₱90,000 - ₱130,000', 'Lead cross-functional teams in delivering technology projects on time and within budget. Manage project scope, resources, and stakeholder communications.', 'PMP certification preferred; 7+ years project management experience; Strong leadership skills; Experience with Agile/Scrum methodologies', 'Full-Time', '2025-12-10', 'careers@accenture.com', NULL, '2025-10-02 01:45:19', 'Open'),
(6, 2, 'Network Engineer', 'Mid-Level', 'normal', 4, '₱45,000 - ₱65,000', 'Maintain and optimize our telecommunications network infrastructure. Troubleshoot network issues and implement improvements.', '3+ years experience in network engineering; CCNA or CCNP certification; Knowledge of routing, switching, and network security', 'Full-Time', '2025-11-28', 'careers@globe.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(7, 2, 'Customer Service Representative', 'Entry Level', 'deaf', 8, '₱18,000 - ₱25,000', 'Provide excellent customer support through chat and email channels. Inclusive workplace with full accessibility for deaf employees.', 'High school diploma or equivalent; Excellent written communication; Basic computer skills; Sign language interpreters provided', 'Full-Time', '2025-11-20', 'careers@globe.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(8, 2, 'Digital Marketing Specialist', 'Junior', 'normal', 3, '₱30,000 - ₱45,000', 'Execute digital marketing campaigns across social media, email, and web channels. Analyze campaign performance and optimize strategies.', 'Bachelor\'s degree in Marketing or related field; Experience with Google Analytics and social media platforms; Creative thinking skills', 'Full-Time', '2025-12-05', 'careers@globe.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(9, 2, 'IT Security Analyst', 'Senior', 'normal', 2, '₱70,000 - ₱100,000', 'Protect our telecommunications infrastructure from cyber threats. Monitor security systems and respond to incidents.', '5+ years in cybersecurity; CISSP or CEH certification preferred; Knowledge of security frameworks and threat intelligence', 'Full-Time', '2025-12-15', 'careers@globe.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(10, 2, 'Retail Store Manager', 'Managerial', 'normal', 5, '₱40,000 - ₱60,000', 'Manage Globe retail stores and lead sales teams to achieve targets. Ensure excellent customer experience and store operations.', '3+ years retail management experience; Strong leadership and sales skills; Customer service oriented', 'Full-Time', '2025-11-30', 'careers@globe.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(11, 3, 'Production Supervisor', 'Mid-Level', 'normal', 6, '₱35,000 - ₱50,000', 'Oversee daily production operations in our manufacturing facilities. Ensure quality standards and safety protocols are maintained.', '3+ years in manufacturing/production; Knowledge of quality control processes; Strong leadership skills', 'Full-Time', '2025-11-25', 'jobs@smc.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(12, 3, 'Quality Assurance Technician', 'Entry Level', 'deaf', 10, '₱20,000 - ₱28,000', 'Conduct quality inspections of products and raw materials. Fully accessible workplace for deaf and hard-of-hearing employees.', 'Technical or vocational course graduate; Attention to detail; Basic understanding of quality standards; Assistive devices provided', 'Full-Time', '2025-11-22', 'jobs@smc.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(13, 3, 'Supply Chain Analyst', 'Junior', 'normal', 4, '₱30,000 - ₱42,000', 'Analyze supply chain data to optimize inventory levels and logistics operations across our nationwide network.', 'Bachelor\'s degree in Business, Logistics, or related field; Proficiency in Excel; Analytical thinking skills', 'Full-Time', '2025-12-01', 'jobs@smc.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(14, 3, 'Mechanical Engineer', 'Senior', 'normal', 3, '₱60,000 - ₱85,000', 'Design and maintain mechanical systems in our production facilities. Lead engineering projects and improvements.', 'Licensed Mechanical Engineer; 5+ years experience in manufacturing; AutoCAD proficiency', 'Full-Time', '2025-12-10', 'jobs@smc.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(15, 3, 'Sales Executive', 'Junior', 'normal', 8, '₱25,000 - ₱40,000 + Commission', 'Promote and sell SMC products to retailers and distributors. Build and maintain strong client relationships.', 'Bachelor\'s degree; Sales experience preferred; Excellent communication skills; Valid driver\'s license', 'Full-Time', '2025-11-28', 'jobs@smc.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(16, 4, 'Financial Analyst', 'Mid-Level', 'normal', 3, '₱50,000 - ₱70,000', 'Provide financial analysis and insights to support business decisions across Ayala\'s diverse portfolio of companies.', 'Bachelor\'s degree in Finance or Accounting; CPA or CFA preferred; 3+ years experience in financial analysis', 'Full-Time', '2025-12-05', 'recruitment@ayala.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(17, 4, 'Human Resources Specialist', 'Junior', 'normal', 4, '₱28,000 - ₱40,000', 'Support HR operations including recruitment, employee relations, and benefits administration.', 'Bachelor\'s degree in HR or Psychology; Strong interpersonal skills; Knowledge of labor laws', 'Full-Time', '2025-11-30', 'recruitment@ayala.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(18, 4, 'Real Estate Developer', 'Senior', 'normal', 2, '₱80,000 - ₱120,000', 'Lead real estate development projects from planning to execution. Manage stakeholder relationships and project timelines.', '7+ years in real estate development; Strong project management skills; Knowledge of zoning and building regulations', 'Full-Time', '2025-12-15', 'recruitment@ayala.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(19, 4, 'Sustainability Officer', 'Mid-Level', 'deaf', 2, '₱45,000 - ₱60,000', 'Implement sustainability programs across Ayala businesses. Monitor environmental impact and promote green initiatives. Inclusive workplace for all.', 'Bachelor\'s degree in Environmental Science or related field; 3+ years experience; Passion for sustainability; Full accessibility support', 'Full-Time', '2025-12-01', 'recruitment@ayala.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(20, 4, 'Corporate Strategy Manager', 'Managerial', 'normal', 2, '₱100,000 - ₱150,000', 'Develop and execute corporate strategies to drive growth and competitive advantage across the Ayala conglomerate.', 'MBA preferred; 8+ years in strategy or management consulting; Excellent analytical and presentation skills', 'Full-Time', '2025-12-20', 'recruitment@ayala.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(21, 5, 'Restaurant Manager', 'Managerial', 'normal', 10, '₱30,000 - ₱45,000', 'Manage daily restaurant operations, lead teams, and ensure excellent customer service at our Jollibee stores.', 'Bachelor\'s degree preferred; 2+ years restaurant management experience; Strong leadership skills; Customer service oriented', 'Full-Time', '2025-11-25', 'hiring@jollibee.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(22, 5, 'Crew Member', 'Entry Level', 'deaf', 20, '₱16,000 - ₱20,000', 'Provide friendly service to customers and support restaurant operations. Welcoming workplace for deaf employees with full support.', 'High school graduate; Positive attitude; Willingness to learn; Sign language training provided', 'Part-Time', '2025-11-20', 'hiring@jollibee.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(23, 5, 'Marketing Coordinator', 'Junior', 'normal', 4, '₱28,000 - ₱38,000', 'Support marketing campaigns and promotional activities for Jollibee brand. Coordinate with agencies and internal teams.', 'Bachelor\'s degree in Marketing; Creative thinking; Good project management skills; Familiarity with social media', 'Full-Time', '2025-12-01', 'hiring@jollibee.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(24, 5, 'Supply Chain Manager', 'Senior', 'normal', 3, '₱70,000 - ₱95,000', 'Manage supply chain operations ensuring timely delivery of food supplies to all Jollibee stores nationwide.', '5+ years supply chain management experience; Knowledge of food safety standards; Strong negotiation skills', 'Full-Time', '2025-12-10', 'hiring@jollibee.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(25, 5, 'Food Technologist', 'Mid-Level', 'normal', 3, '₱40,000 - ₱55,000', 'Develop and improve food products. Ensure quality standards and work on new menu innovations.', 'Bachelor\'s degree in Food Technology; 3+ years experience in food industry; Knowledge of food safety regulations', 'Full-Time', '2025-12-05', 'hiring@jollibee.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(26, 6, 'Store Supervisor', 'Mid-Level', 'normal', 12, '₱25,000 - ₱35,000', 'Supervise store operations, manage staff, and ensure excellent customer service at Puregold branches.', 'Bachelor\'s degree; 2+ years retail experience; Strong leadership and organizational skills', 'Full-Time', '2025-11-28', 'careers@puregold.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(27, 6, 'Cashier', 'Entry Level', 'deaf', 15, '₱16,000 - ₱22,000', 'Process customer transactions accurately and efficiently. Inclusive environment for deaf employees.', 'High school graduate; Basic math skills; Honest and trustworthy; Visual communication support provided', 'Full-Time', '2025-11-18', 'careers@puregold.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(28, 6, 'Inventory Analyst', 'Junior', 'normal', 5, '₱22,000 - ₱32,000', 'Monitor inventory levels, analyze stock movement, and coordinate with suppliers to maintain optimal inventory.', 'Bachelor\'s degree; Proficiency in Excel; Attention to detail; Good analytical skills', 'Full-Time', '2025-12-01', 'careers@puregold.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(29, 6, 'Purchasing Manager', 'Senior', 'normal', 3, '₱55,000 - ₱75,000', 'Manage procurement operations and negotiate with suppliers to secure best prices for our retail operations.', '5+ years purchasing experience; Strong negotiation skills; Knowledge of retail operations', 'Full-Time', '2025-12-08', 'careers@puregold.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(30, 6, 'Visual Merchandiser', 'Junior', 'normal', 6, '₱20,000 - ₱30,000', 'Create attractive product displays that drive sales. Implement merchandising strategies across stores.', 'Diploma in Marketing or related field; Creative eye for design; Understanding of consumer behavior', 'Full-Time', '2025-11-30', 'careers@puregold.com.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(31, 7, 'Registered Nurse', 'Entry Level', 'normal', 15, '₱25,000 - ₱35,000', 'Provide nursing care in DOH facilities. Assist in implementing health programs and patient care services.', 'Licensed Registered Nurse; Fresh graduates welcome; Compassionate and patient-oriented', 'Full-Time', '2025-12-15', 'jobs@doh.gov.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(32, 7, 'Medical Technologist', 'Junior', 'deaf', 8, '₱22,000 - ₱32,000', 'Conduct laboratory tests and analyses. Inclusive workplace for deaf professionals with full support.', 'Licensed Medical Technologist; Laboratory experience preferred; Attention to detail; Assistive technology available', 'Full-Time', '2025-12-01', 'jobs@doh.gov.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(33, 7, 'Public Health Officer', 'Mid-Level', 'normal', 10, '₱35,000 - ₱50,000', 'Implement public health programs in communities. Monitor health indicators and coordinate disease prevention initiatives.', 'Medical Doctor or Public Health degree; 3+ years experience; Strong community engagement skills', 'Full-Time', '2025-12-10', 'jobs@doh.gov.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(34, 7, 'Health Information Officer', 'Junior', 'normal', 6, '₱24,000 - ₱35,000', 'Manage health records and information systems. Ensure data accuracy and confidentiality.', 'Bachelor\'s degree in Health Information Management; Knowledge of health information systems; Detail-oriented', 'Full-Time', '2025-11-30', 'jobs@doh.gov.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(35, 7, 'Hospital Administrator', 'Managerial', 'normal', 4, '₱60,000 - ₱85,000', 'Oversee hospital operations and management. Ensure quality healthcare delivery and regulatory compliance.', 'Master\'s degree in Healthcare Administration; 5+ years hospital management experience; Strong leadership skills', 'Full-Time', '2025-12-20', 'jobs@doh.gov.ph', NULL, '2025-10-02 01:45:19', 'Open'),
(36, 8, 'Customer Service Representative', 'Entry Level', 'normal', 50, '₱18,000 - ₱25,000', 'Handle customer inquiries via phone, chat, and email. Provide excellent customer support for international clients.', 'College graduate or undergraduate; Excellent English communication; Computer literate; Willing to work on shifts', 'Full-Time', '2025-11-22', 'hr@concentrix.com', NULL, '2025-10-02 01:45:19', 'Open'),
(37, 8, 'Technical Support Specialist', 'Junior', 'deaf', 20, '₱22,000 - ₱30,000', 'Provide technical support through chat and email channels. Inclusive BPO environment for deaf employees.', 'Technical background preferred; Good written English; Problem-solving skills; Full accessibility support provided', 'Full-Time', '2025-11-25', 'hr@concentrix.com', NULL, '2025-10-02 01:45:19', 'Open'),
(38, 8, 'Team Leader', 'Mid-Level', 'normal', 15, '₱35,000 - ₱50,000', 'Lead and coach a team of customer service representatives. Monitor performance and ensure quality standards.', '2+ years BPO experience; Strong leadership and coaching skills; Excellent communication', 'Full-Time', '2025-12-01', 'hr@concentrix.com', NULL, '2025-10-02 01:45:19', 'Open'),
(39, 8, 'Workforce Analyst', 'Mid-Level', 'normal', 8, '₱32,000 - ₱45,000', 'Forecast staffing requirements and optimize workforce schedules. Analyze productivity data and trends.', 'Bachelor\'s degree; 2+ years workforce management experience; Proficiency in Excel and WFM tools', 'Full-Time', '2025-12-05', 'hr@concentrix.com', NULL, '2025-10-02 01:45:19', 'Open'),
(40, 8, 'Operations Manager', 'Managerial', 'normal', 5, '₱70,000 - ₱100,000', 'Manage BPO operations and lead multiple teams. Drive performance improvement and client satisfaction.', '5+ years BPO management experience; Strong leadership and client management skills; Results-driven', 'Full-Time', '2025-12-15', 'hr@concentrix.com', NULL, '2025-10-02 01:45:19', 'Open'),
(41, 9, 'Police Officer I', 'Entry Level', 'normal', 100, '₱29,668 (SG 11)', 'Maintain peace and order in communities. Respond to emergencies and enforce laws. Serve and protect the Filipino people.', 'Filipino citizen; 21-30 years old; Bachelor\'s degree holder; Height requirement: Male 5\'4\", Female 5\'2\"; Physically and mentally fit; PNPA or NAPOLCOM eligible', 'Full-Time', '2025-12-01', 'recruitment@pnp.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(42, 9, 'Intelligence Analyst', 'Junior', 'normal', 10, '₱32,053 (SG 12)', 'Analyze intelligence data to support law enforcement operations. Prepare reports and threat assessments.', 'Bachelor\'s degree in Criminology or related field; Analytical skills; Discretion and integrity', 'Full-Time', '2025-12-10', 'recruitment@pnp.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(43, 9, 'Forensic Specialist', 'Mid-Level', 'normal', 8, '₱38,813 (SG 15)', 'Conduct forensic examinations and crime scene investigations. Provide expert testimony in court.', 'Bachelor\'s degree in Forensic Science; 3+ years experience; Strong attention to detail', 'Full-Time', '2025-12-15', 'recruitment@pnp.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(44, 9, 'IT Security Specialist', 'Mid-Level', 'deaf', 5, '₱35,097 (SG 13)', 'Maintain cybersecurity systems for PNP operations. Monitor threats and implement security protocols. Inclusive workplace.', 'Bachelor\'s degree in IT; Cybersecurity certification preferred; 2+ years experience; Full support for deaf employees', 'Full-Time', '2025-12-08', 'recruitment@pnp.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(45, 9, 'Community Relations Officer', 'Mid-Level', 'normal', 12, '₱38,813 (SG 15)', 'Build relationships between PNP and communities. Implement community policing programs and public safety initiatives.', 'Bachelor\'s degree; 3+ years experience in community development; Strong interpersonal skills', 'Full-Time', '2025-12-05', 'recruitment@pnp.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(46, 10, 'Research Assistant', 'Entry Level', 'normal', 15, '₱23,877 (SG 8)', 'Support research projects in various science and technology fields. Conduct experiments and data analysis.', 'Bachelor\'s degree in Science or Engineering; Fresh graduates welcome; Research-oriented; Analytical mindset', 'Full-Time', '2025-12-01', 'jobs@dost.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(47, 10, 'Science Research Specialist', 'Mid-Level', 'deaf', 8, '₱38,813 (SG 15)', 'Lead research projects and publish findings. Contribute to DOST\'s science and technology initiatives. Fully accessible workplace.', 'Master\'s degree in Science or Engineering; 3+ years research experience; Published papers preferred; Full accessibility support', 'Full-Time', '2025-12-10', 'jobs@dost.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(48, 10, 'Technology Transfer Officer', 'Mid-Level', 'normal', 6, '₱35,097 (SG 13)', 'Facilitate transfer of DOST technologies to industries. Promote commercialization of research outputs.', 'Bachelor\'s degree; 3+ years experience in technology commercialization; Strong networking skills', 'Full-Time', '2025-12-08', 'jobs@dost.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(49, 10, 'Data Scientist', 'Senior', 'normal', 4, '₱51,566 (SG 18)', 'Analyze complex datasets to support evidence-based policy making. Develop predictive models for DOST programs.', 'Master\'s degree in Data Science or related field; 5+ years experience; Proficiency in Python, R, machine learning', 'Full-Time', '2025-12-15', 'jobs@dost.gov.ph', NULL, '2025-10-02 01:45:20', 'Open'),
(50, 10, 'Program Director', 'Managerial', 'normal', 3, '₱82,006 (SG 24)', 'Lead major science and technology programs. Manage budgets and coordinate with stakeholders to achieve national S&T goals.', 'PhD preferred; 10+ years experience; Strong leadership and strategic planning skills; Proven track record in program management', 'Full-Time', '2025-12-20', 'jobs@dost.gov.ph', NULL, '2025-10-02 01:45:20', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Graduate','Employer','Admin') DEFAULT 'Graduate',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `email_address`, `password`, `user_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'E100001', 'hr@accenture.com', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(2, 'E100002', 'careers@globe.com.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(3, 'E100003', 'jobs@smc.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(4, 'E100004', 'recruitment@ayala.com', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(5, 'E100005', 'hiring@jollibee.com', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(6, 'E100006', 'careers@puregold.com.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(7, 'E100007', 'jobs@doh.gov.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(8, 'E100008', 'hr@concentrix.com', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(9, 'E100009', 'recruitment@pnp.gov.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16'),
(10, 'E100010', 'jobs@dost.gov.ph', 'test123', 'Employer', 'Active', '2025-10-01 09:28:16', '2025-10-01 09:28:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_job_id` (`job_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `graduate_information`
--
ALTER TABLE `graduate_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `graduate_information`
--
ALTER TABLE `graduate_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_listings`
--
ALTER TABLE `job_listings`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`job_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `graduate_information`
--
ALTER TABLE `graduate_information`
  ADD CONSTRAINT `graduate_information_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD CONSTRAINT `job_listings_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
