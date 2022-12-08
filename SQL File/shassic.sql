-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 04, 2022 at 10:32 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shassic`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessor_id` int(11) NOT NULL,
  `assessor_name` varchar(200) NOT NULL,
  `assessee_id` int(11) NOT NULL,
  `assessee_name` varchar(200) NOT NULL,
  `project_name` varchar(200) NOT NULL,
  `project_date` date NOT NULL,
  `project_location` varchar(200) DEFAULT NULL,
  `project_picture` varchar(100) DEFAULT NULL,
  `assessment_progress` int(11) NOT NULL,
  `calculation_id` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT current_timestamp(),
  `updation_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `document_check_percentage` int(11) DEFAULT NULL,
  `workplace_inspection_percentage` int(11) DEFAULT NULL,
  `personnel_interview_percentage` int(11) DEFAULT NULL,
  `star_ranking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `document_check_assessment`
--

CREATE TABLE `document_check_assessment` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `document_check_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `document_check_checklist`
--

CREATE TABLE `document_check_checklist` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `checklist` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_check_checklist`
--

INSERT INTO `document_check_checklist` (`id`, `item_id`, `checklist`) VALUES
(1, 1, 'Whether there is a written Project OSH Policy Statement?'),
(2, 1, 'Has the SHC conducted a review to ensure suitability of Project OSH Policy Statement?'),
(3, 1, 'Whether Project OSH Policy Statement was written in Bahasa Malaysia?'),
(4, 1, 'Whether Project OSH Policy Statement was written in English?'),
(5, 1, 'Whether Project OSH Policy is signed by the top management?'),
(6, 1, 'Whether the Project OSH Policy is dated?'),
(7, 1, 'Whether there is any evidences Project OSH \r\nPolicy being communicated to workers?'),
(8, 2, 'Are there documented HIRARC?'),
(9, 2, 'Whether HIRARC has been reviewed and updated?'),
(10, 3, 'Are there documented legal requirements and other requirements Action Plan?'),
(11, 3, 'Is there a Legal Compliance Checklist & updated accordingly?'),
(12, 3, 'Is there a valid CIDB Registration Certificate?'),
(13, 3, 'Whether construction personnel registered with CIDB and holding a valid CIDB’s Personnel Registration Card?'),
(14, 3, 'Whether Project site registered with DOSH?'),
(15, 4, 'Whether OH&S objectives evaluated from time to time?'),
(16, 4, 'OH&S objectives are integrated in other work activities or procurement process?'),
(17, 5, 'Whether the roles and responsibilities of all project’s personnel documented?'),
(18, 5, 'Whether the roles and responsibilities of all project’s personnel communicated to them?'),
(19, 5, 'Whether the project SHC is established in \r\naccordance with OSH (SHC) 1996 Regulations?'),
(20, 5, 'Whether there is SSS appointed on site?'),
(21, 5, 'Designated Persons (DP) are assigned for \r\nrespective BOWEC work activities.'),
(22, 5, 'There is a SHO employed for the project, which the contract sum is RM20 million and above.'),
(23, 6, 'There is appropriate documented information on employee’s competency.'),
(24, 6, 'There is OSH Induction Training conducted.'),
(25, 6, 'Necessary competency and training required for employees have been identified.'),
(26, 6, 'There is Competence, Training and Awareness Action Plan.'),
(27, 6, 'Requirement on Competency, Training and \r\nAwareness is made known to employees.'),
(28, 7, 'There is proof of communication related to OH&S Management System.'),
(29, 7, 'There is proof of workers’ participation and consultation.'),
(30, 7, 'Safety and Health Committee is established and meeting conducted on monthly basis.'),
(31, 7, 'Safety and Health Committee is involved for improvement of the OH&S Management\r\nSystem.'),
(32, 8, 'There is a written directive for creating and updating document information.'),
(33, 8, 'There is a register / log for all available OH&S Management System.'),
(34, 8, 'There is Action Plan for storage, protection, retrieval, retention and disposal of records.'),
(35, 9, 'There are document on hazard elimination and risk reduction using Hierarchy of Control.'),
(36, 9, 'There are SWP or SWI or SOP for three (3) high-risk work activities.'),
(37, 9, 'There is Action Plan on controlling temporary or permanent changes of work processes.'),
(38, 10, 'There is document(s) in potential emergency situation identification and initial evaluation.'),
(39, 10, 'There is a project Emergency Response Plan developed.'),
(40, 10, 'There is a project Emergency Response Team established and trained.'),
(41, 11, 'There is an Action Plan developed to monitor, measure, analyse and evaluate Oh&S performance.'),
(42, 11, 'Reports on monitoring, measuring, analysing and evaluating OH&S performance are reviewed by project top management.'),
(43, 11, 'There is Action Plan developed to evaluate legal requirements and other requirements compliances.'),
(44, 12, 'There is an Action Plan for reporting, investigating and taking action related to incident and Nonconformity determination and managing.'),
(45, 12, 'Incident record / logbook / register is established.'),
(46, 13, 'There is an Action Plan for Internal Audit program established, implemented and maintained.'),
(47, 13, 'DOSH Site Visit or Inspection Log Book is \r\nmaintained.'),
(48, 13, 'Audit Report is reported to relevant managers for their further action.'),
(49, 13, 'There is action taken to address Nonconformity and continual improvement of OH&S performance.'),
(50, 14, 'Records on Temporary & Falsework are checked and approved prior to use.'),
(51, 14, 'Site preparation - Safe Work Procedures'),
(52, 14, 'Certificate of Fitness (PMA)'),
(53, 14, 'Certificate of Fitness (PMT)'),
(54, 14, 'Machinery maintenance record'),
(55, 14, 'Local authorities’ approval to clear bush, remove top soil or felling of trees'),
(56, 14, 'Hoarding erection'),
(57, 14, 'Fogging');

-- --------------------------------------------------------

--
-- Table structure for table `document_check_section`
--

CREATE TABLE `document_check_section` (
  `id` int(11) NOT NULL,
  `item_no` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_check_section`
--

INSERT INTO `document_check_section` (`id`, `item_no`, `item_name`) VALUES
(1, 'A', 'PROJECT OSH POLICY'),
(2, 'B', 'HIRARC'),
(3, 'C', 'DETERMINATION OF LEGAL REQUIREMENTS AND OTHER REQUIREMENTS'),
(4, 'D', 'OCCUPATIONAL HEALTH & SAFETY OBJECTIVES'),
(5, 'E', 'OH&S ROLES AND RESPONSIBILITIES'),
(6, 'F', 'COMPETENCE TRAINING AND AWARENESS'),
(7, 'G', 'COMMUNICATION, PARTICIPATION AND CONSULTATION'),
(8, 'H', 'DOCUMENTED INFORMATION'),
(9, 'I', 'OPERATIONAL PLANNING AND CONTROL'),
(10, 'J', 'EMERGENCY PREPARENESS AND RESPONSE'),
(11, 'K', 'PERFORMANCE MEASUREMENT AND MONITORING'),
(12, 'L', 'INCIDENT, NONCONFORMITY AND CORRECTIVE ACTION'),
(13, 'M', 'INTERNAL AUDIT PROGRAMME'),
(14, 'N', 'CONSTRUCTION WORK ACTIVITIES');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_category`
--

CREATE TABLE `personnel_interview_category` (
  `id` int(11) NOT NULL,
  `category_no` varchar(200) NOT NULL,
  `category_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `personnel_interview_category`
--

INSERT INTO `personnel_interview_category` (`id`, `category_no`, `category_name`) VALUES
(1, 'A', 'MANAGERIAL'),
(2, 'B', 'SUPERVISORY'),
(3, 'C', 'WORKERS');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_checklist`
--

CREATE TABLE `personnel_interview_checklist` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `checklist` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `personnel_interview_checklist`
--

INSERT INTO `personnel_interview_checklist` (`id`, `category_id`, `checklist`) VALUES
(1, 1, 'Are prevention of work related injury and/or health and the provision of safety and health workplaces and activities are committed / demonstrated by top management?'),
(2, 1, 'Are workers consultation and participation in OH&S program and activities assured by top management?'),
(3, 1, 'Who are the chairman and secretary for SHC meeting?'),
(4, 1, 'There is an organisational culture that supports the expected results of the OH&S MS?'),
(5, 1, 'What do you know about HIRARC?'),
(6, 1, 'Have you ever been involved in HIRARC exercise?'),
(7, 1, 'What is NADOPOD Regulations 2004?'),
(8, 1, 'How is the Project OH&S performance being evaluated?'),
(9, 1, 'Are you personally involved in OH&S management review and continual improvement for this project?'),
(10, 2, 'What do you know about work hazard, risk evaluation and control?'),
(11, 2, 'When do you review your risk control measure?'),
(12, 2, 'How often is the SHC meeting conducted?'),
(13, 2, 'Who is the Chairman of the committee?'),
(14, 2, 'Have you attended any related safety and health training during the project period?'),
(15, 2, 'Have you attended any accident investigation training?'),
(16, 2, 'Are you involved in the safety training / promotion at the site?'),
(17, 2, 'Do you check the temporary structures are installed and fixed according to its intended use or PE design?'),
(18, 3, 'Project OSH Policy is communicated to you?'),
(19, 3, 'Hazard and prevention of your work is communicated to you?'),
(20, 3, 'Do you know who is the Safety and Health Officer / Site Safety Supervisor?'),
(21, 3, 'Have you ever heard about workplace SHC?'),
(22, 3, 'Do you hold CIDB’s Personnel Registration Card?'),
(23, 3, 'Have you undergone specific workplace induction course?'),
(24, 3, 'Have you ever attended a toolbox meeting?'),
(25, 3, 'Have you participated in any Safety Campaign?'),
(26, 3, 'Are you a competent operator with valid \r\nlicense / authorised to operate this\r\nmachinery / equipment / vehicle?'),
(27, 3, 'Is there a safety checklist for you to fill up prior to operating the machinery?'),
(28, 3, 'How do you handle chemicals / hazardous materials at the site?'),
(29, 3, 'Do you know what to do during an emergency/accident?'),
(30, 3, 'What would you do if you witness someone injured?'),
(31, 3, 'Can you show me the assembly point?'),
(32, 3, 'Can you identify your First Aider?'),
(33, 3, 'Where is the location of the First-Aid Box?'),
(34, 3, 'Where is the nearest Fire Extinguisher?');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_managerial`
--

CREATE TABLE `personnel_interview_managerial` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_supervisory_1`
--

CREATE TABLE `personnel_interview_supervisory_1` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_supervisory_2`
--

CREATE TABLE `personnel_interview_supervisory_2` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_supervisory_3`
--

CREATE TABLE `personnel_interview_supervisory_3` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_1`
--

CREATE TABLE `personnel_interview_worker_1` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_2`
--

CREATE TABLE `personnel_interview_worker_2` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_3`
--

CREATE TABLE `personnel_interview_worker_3` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_4`
--

CREATE TABLE `personnel_interview_worker_4` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_5`
--

CREATE TABLE `personnel_interview_worker_5` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_6`
--

CREATE TABLE `personnel_interview_worker_6` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_7`
--

CREATE TABLE `personnel_interview_worker_7` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_8`
--

CREATE TABLE `personnel_interview_worker_8` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_interview_worker_9`
--

CREATE TABLE `personnel_interview_worker_9` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `personnel_interview_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `password_code` mediumint(50) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `code`, `fullname`, `password_code`, `creation_date`) VALUES
(1, 'irmazafirah', 'irmazafirah@graduate.utm.my', '$2y$10$n81EAVqUxBmB6tVB1d5Qme7tpZ1ezWEs.cAWrpKubqIxXf0vw1Qmq', '1234', 'Irma Zafirah Mohd Ikram', 0, '2022-11-08 01:27:04'),
(2, 'syahidatul', 'nursyahidatulasyiqin@graduate.utm.my', '$2y$10$pqqxZ84JgZAUmzkkoYwrpeQxLzQgnAS0hbV.N1OsenbdVq2x8NkjO', '1235', 'Nursyahidatul Asyiqin', 0, '2022-11-10 01:10:51'),
(3, 'aqilah', 'nrlaqilahahmd@gmail.com', '$2y$10$lKdwOzZ2NwyN0OYyGmbgAei4YVKW2p0ikNyd/JxHIILYKHvNrNDpa', '1236', 'Nurul Aqilah Ahmad', 0, '2022-11-10 01:12:31'),
(4, 'nuraqilah.a', 'nurulaqilah.a@graduate.utm.my', '$2y$10$NavI8gq178qMROS5iQuOJ.GQVk8bX0Swzod2kICUYyULBlpsGizSG', '1118', 'Nur Aqilah', 0, '2022-11-10 01:20:13'),
(5, 'arinie', 'arinie@gmail.com', '$2y$10$YmjJe.ABktcpRrVceoAQfezehxgzaEHRYcDHXwkcYseu7F.F40zLe', '1111', 'Noor Arinie Norhalil', 0, '2022-11-10 08:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_assessment`
--

CREATE TABLE `workplace_inspection_assessment` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `workplace_inspection_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_checklist`
--

CREATE TABLE `workplace_inspection_checklist` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `checklist` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workplace_inspection_checklist`
--

INSERT INTO `workplace_inspection_checklist` (`id`, `item_id`, `checklist`) VALUES
(1, 1, 'There is displayed Project Safety OH&S Policy and communicated to all employees.'),
(2, 1, 'Project site JKKP registration number displayed at\r\nsite.'),
(3, 1, 'Are there any OSH Site Rules displayed on project\r\nsite?'),
(4, 2, 'Whether traffic movement plan developed?'),
(5, 3, 'Mandatory Signage in BLUE'),
(6, 3, 'Prohibition Signage in RED'),
(7, 3, 'Warning Signage in YELLOW'),
(8, 3, 'Safe Signage in GREEN'),
(9, 3, 'Assembly point with signage'),
(10, 4, 'Competent and Trained Person'),
(11, 5, 'Approved PE Designed'),
(12, 5, 'Certificate of Fitness (PMA / PMT)'),
(13, 5, 'Competent/Trained Operator'),
(14, 5, 'Machinery Guarding/Fencing'),
(15, 5, 'Trained rigger assigned?'),
(16, 6, 'Material Handling Arrangement'),
(17, 6, 'Material Storage Arrangement'),
(18, 6, 'Safety Data Sheet (SDS)'),
(19, 6, 'Waste Material are disposed as planned'),
(20, 7, 'Safety/Notices Signage displayed'),
(21, 7, 'Permit to Work issued for high-risk activities?'),
(22, 7, 'Safe Access and Egress'),
(23, 7, 'Ventilation'),
(24, 7, 'Fall protection against falling from height (2 meter and more) available during \r\nconstruction activities'),
(25, 7, 'Adequate Illumination'),
(26, 7, 'Movement of Vehicular Traffic is in order'),
(27, 7, 'Temporary & falsework are installed, fixed \r\nand constructed as per the intended purpose / or as per PE’s design.'),
(28, 7, 'Workers are using appropriate PPE'),
(29, 7, 'Standing supervision by SSS / DP for hazardous activities e.g. working at height, mechanical lifting or etc.'),
(30, 7, 'Openings inside building are properly guarded and secured from falling of objects / worker.');

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_high_risk_1`
--

CREATE TABLE `workplace_inspection_high_risk_1` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `workplace_inspection_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_high_risk_2`
--

CREATE TABLE `workplace_inspection_high_risk_2` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `workplace_inspection_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_high_risk_3`
--

CREATE TABLE `workplace_inspection_high_risk_3` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `workplace_inspection_checklist_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'C / NC / NA',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_section`
--

CREATE TABLE `workplace_inspection_section` (
  `id` int(11) NOT NULL,
  `item_no` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workplace_inspection_section`
--

INSERT INTO `workplace_inspection_section` (`id`, `item_no`, `item_name`) VALUES
(1, 'A', 'SITE OSH MANAGEMENT'),
(2, 'B', 'MOVEMENT OF VEHICULAR TRAFFIC'),
(3, 'C', 'SIGNAGE AND COLOUR CODE'),
(4, 'D', 'DESIGNATED PERSON APPOINTED'),
(5, 'E', 'EQUIPMENT/MACHINERY'),
(6, 'F', 'MATERIAL'),
(7, 'G', 'WORKING ENVIRONMENT');

-- --------------------------------------------------------

--
-- Table structure for table `workplace_inspection_subscore`
--

CREATE TABLE `workplace_inspection_subscore` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `general_score` int(11) NOT NULL,
  `high_risk_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessee_id`);

--
-- Indexes for table `document_check_assessment`
--
ALTER TABLE `document_check_assessment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_check_checklist`
--
ALTER TABLE `document_check_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_check_section`
--
ALTER TABLE `document_check_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_category`
--
ALTER TABLE `personnel_interview_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_checklist`
--
ALTER TABLE `personnel_interview_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_managerial`
--
ALTER TABLE `personnel_interview_managerial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_supervisory_1`
--
ALTER TABLE `personnel_interview_supervisory_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_supervisory_2`
--
ALTER TABLE `personnel_interview_supervisory_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_supervisory_3`
--
ALTER TABLE `personnel_interview_supervisory_3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_1`
--
ALTER TABLE `personnel_interview_worker_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_2`
--
ALTER TABLE `personnel_interview_worker_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_3`
--
ALTER TABLE `personnel_interview_worker_3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_4`
--
ALTER TABLE `personnel_interview_worker_4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_5`
--
ALTER TABLE `personnel_interview_worker_5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_6`
--
ALTER TABLE `personnel_interview_worker_6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_7`
--
ALTER TABLE `personnel_interview_worker_7`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_8`
--
ALTER TABLE `personnel_interview_worker_8`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_interview_worker_9`
--
ALTER TABLE `personnel_interview_worker_9`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `workplace_inspection_assessment`
--
ALTER TABLE `workplace_inspection_assessment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplace_inspection_checklist`
--
ALTER TABLE `workplace_inspection_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplace_inspection_high_risk_1`
--
ALTER TABLE `workplace_inspection_high_risk_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplace_inspection_section`
--
ALTER TABLE `workplace_inspection_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplace_inspection_subscore`
--
ALTER TABLE `workplace_inspection_subscore`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_check_assessment`
--
ALTER TABLE `document_check_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_check_checklist`
--
ALTER TABLE `document_check_checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `document_check_section`
--
ALTER TABLE `document_check_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personnel_interview_category`
--
ALTER TABLE `personnel_interview_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personnel_interview_checklist`
--
ALTER TABLE `personnel_interview_checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `personnel_interview_managerial`
--
ALTER TABLE `personnel_interview_managerial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_supervisory_1`
--
ALTER TABLE `personnel_interview_supervisory_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_supervisory_2`
--
ALTER TABLE `personnel_interview_supervisory_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_supervisory_3`
--
ALTER TABLE `personnel_interview_supervisory_3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_1`
--
ALTER TABLE `personnel_interview_worker_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_2`
--
ALTER TABLE `personnel_interview_worker_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_3`
--
ALTER TABLE `personnel_interview_worker_3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_4`
--
ALTER TABLE `personnel_interview_worker_4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_5`
--
ALTER TABLE `personnel_interview_worker_5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_6`
--
ALTER TABLE `personnel_interview_worker_6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_7`
--
ALTER TABLE `personnel_interview_worker_7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_8`
--
ALTER TABLE `personnel_interview_worker_8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel_interview_worker_9`
--
ALTER TABLE `personnel_interview_worker_9`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workplace_inspection_assessment`
--
ALTER TABLE `workplace_inspection_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplace_inspection_checklist`
--
ALTER TABLE `workplace_inspection_checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `workplace_inspection_high_risk_1`
--
ALTER TABLE `workplace_inspection_high_risk_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplace_inspection_section`
--
ALTER TABLE `workplace_inspection_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workplace_inspection_subscore`
--
ALTER TABLE `workplace_inspection_subscore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
