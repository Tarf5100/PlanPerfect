-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 08, 2025 at 03:46 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planperfect`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `categoryname` varchar(100) NOT NULL,
  `categorytype` enum('event','service') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `categoryname`, `categorytype`) VALUES
(1, 'تخرج', 'event'),
(2, 'عزيمة', 'event'),
(3, 'زواج', 'event'),
(4, 'غبقة', 'event'),
(5, 'ميلاد', 'event'),
(6, 'استقبال', 'event'),
(7, 'حلا', 'service'),
(8, 'عشاء', 'service'),
(9, 'ورد وبالونات', 'service'),
(10, 'طقاقات', 'service'),
(11, 'صبابات', 'service'),
(12, 'منظم حفلات', 'service');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favoriteID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favoriteID`, `userID`, `serviceID`) VALUES
(2, 12, 602),
(3, 12, 901),
(4, 13, 701);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `message` text NOT NULL,
  `notificationType` varchar(50) NOT NULL DEFAULT 'general',
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationID`, `userID`, `message`, `notificationType`, `status`, `timestamp`) VALUES
(2, 444, 'قام munira_sultan بإلغاء حجز خدمة تنسيق الزهور والديكور.', 'cancellation', 'unread', '2025-03-27 07:38:21');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `detailID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`detailID`, `orderID`, `serviceID`, `quantity`) VALUES
(1, 1, 201, 1),
(2, 1, 901, 1),
(3, 1, 301, 1),
(4, 2, 1001, 1),
(5, 2, 602, 1),
(6, 2, 701, 1),
(7, 2, 901, 1),
(8, 3, 802, 1),
(9, 3, 902, 1),
(10, 3, 701, 1),
(11, 4, 401, 1),
(12, 5, 1002, 1),
(13, 5, 903, 2),
(22, 10, 103, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `status` enum('انتظار قبول الطلب','قيد التجهيز','تم التوصيل','ملغى') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `createdAt`, `status`) VALUES
(1, 11, '2025-03-24 21:31:41', 'تم التوصيل'),
(2, 12, '2025-03-24 21:31:41', 'تم التوصيل'),
(3, 13, '2025-03-24 21:31:41', 'تم التوصيل'),
(4, 12, '2025-03-24 21:31:41', 'ملغى'),
(5, 13, '2025-03-24 21:31:41', 'قيد التجهيز'),
(10, 11, '2025-04-08 18:15:06', 'قيد التجهيز');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `reviewDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `userID`, `serviceID`, `rating`, `comment`, `reviewDate`) VALUES
(1, 11, 201, 5, 'استخدمت PlanPerfect لتنظيم حفل زفافي، وكانت التجربة رائعة! وفرت الكثير من الوقت والجهد، ووجدت أفضل مزودي الخدمات بأسعار مناسبة.\"', '2025-03-24 11:46:04'),
(2, 12, 1001, 4, 'الباقة كانت مرتبة والأكل لذيذ ما شاء الله، التنسيق يفتح النفس ويبين فيه اهتمام بالتفاصيل. بس تمنيت لو كانت الكمية أكبر شوي. بشكل عام تجربة حلوة وأنصح فيها ', '2025-03-24 11:56:08'),
(3, 13, 802, 5, 'صراحة التنظيم كان فوق المتوقع! الديكور مرررة ناعم وراقي، والتفاصيل كانت تجنن، من الورد للتوزيعات وحتى طاولة الضيافة. الكل مدح التنسيق، وحسسونا إن المناسبة فعلاً مميزة. يعطيهم العافية', '2025-03-24 11:56:08'),
(4, 11, 901, 4, 'التقديم كان أنيق والخيارات متنوعة بين الحالي والمالح، وكل شي وصل مرتب ونظيف. الطعم لذيذ والضيوف انبسطوا، بس تمنيت لو كانت بعض الأصناف أكثر من غيرها. بشكل عام الباقة ممتازة وتستاهل التجربة', '2025-03-24 12:01:00'),
(5, 11, 301, 4, 'التصوير كان جداً احترافي، والزوايا والإضاءة طلعوا الصور بطريقة جميلة وواضحة. المصوره كانت متعاونه وهذا خلانا نرتاح قدام الكاميرا. بس تأخروا شوي في تسليم الصور، غير كذا كل شي ممتاز وأنصح فيهم', '2025-03-24 19:40:07'),
(6, 12, 602, 5, 'العازف كان رائع! أضفى جو رايق ومميز على المناسبة، عزفه كان ناعم واحترافي، وتفاعل مع الطلبات بكل رحابة صدر. فعلاً أضاف لمسة فنية للحفل، الكل انبسط', '2025-03-24 19:46:10'),
(7, 12, 701, 3, 'الخدمة كانت جيدة بشكل عام، الصبابات والعاملات ملتزمات ولبسهم مرتب. لكن حسّيت في بعض التأخير بالتقديم، وكان ممكن يكون فيه تنظيم أكثر. تجربة مقبولة، بس في مجال للتحسين.', '2025-03-24 19:49:49'),
(8, 12, 901, 5, 'الباقة كانت أكثر من رائعة! التنوع بين الحالي والمالح ممتاز والطعم لذيذ جداً، والتنسيق كان أنيق ومرتب بشكل يفتح النفس. الكمية كانت كافية والضيوف انبهروا بالتقديم. أكيد بطلبها مرة ثانية', '2025-03-24 19:54:01'),
(9, 13, 902, 4, 'التقديم كان جميل والحلا متنوع وطعمه لذيذ، واضح الشغل متعوب عليه. بس تمنيت لو كان فيه خيارات أكثر أو توضيح لكل نوع بالصينية. بشكل عام تجربة ممتازة وأنصح فيها', '2025-03-24 19:55:25'),
(10, 13, 701, 5, 'الخدمة كانت ممتازة بكل معنى الكلمة! الصبابات والعاملات مرتبات وشغلهم منظم وراقي، تعاملهم جداً لطيف وكانوا حريصين على راحة الضيوف. فعلاً أضفوا لمسة جميلة على المناسبة، شكراً لهم!', '2025-03-24 19:55:25'),
(11, 13, 802, 5, 'one of the best event planners!', '2025-03-25 02:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `serviceID` int(11) NOT NULL,
  `vendorID` int(11) NOT NULL,
  `serviceName` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(65,0) NOT NULL,
  `imageURL` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`serviceID`, `vendorID`, `serviceName`, `description`, `price`, `imageURL`) VALUES
(101, 111, 'حافظة جريش', 'استمتع بطعم الجريش الأصيل! حافظة كبيرة جريش، تحضر بمكونات طازجة ونكهة تقليدية غنية. مثالية للولائم، تحفظ الحرارة وتوصلك بألذ طعم.', '150', 'images/jarish.jpg'),
(102, 111, 'حافظة مرقوق', 'مرقوق على أصوله! حافظة كبيرة مرقوق شهي محضر بعجينة طازجة وخضار موسمية، مع تتبيلة غنية ونكهة تقليدية لا تقاوم. تقدم في حافظة تحفظ الطعم والحرارة، مثالية للعزائم.\r\n\r\n', '200', 'images/margog.jpg'),
(103, 111, 'حافظة قرصان', 'قرصان لذيذ بنكهته الشعبية الأصيلة! يحضر بخبز القرصان الطازج المطهو على البخار، مع خضار مشكلة وتتبيلة تقليدية تعبر عن طعم نجد الأصيل. يقدم في حافظة تحافظ على الحرارة والنكهة، مثالي للعزايم.', '180', 'images/qersan.jpg'),
(201, 222, 'تنظيم حفلات الزفاف الفاخرة', 'نقدم لك تجربة استثنائية في تنظيم حفلات الزفاف، تشمل التخطيط الدقيق، وتصميم الديكور الراقي، والتنسيق الكامل لجميع تفاصيل المناسبة — لتعيشي يومك الكبير بأناقة وراحة تامة.\r\n\r\n', '15000', 'images/fwedding.jpg'),
(202, 222, 'تنظيم حفلات الزفاف البسيطة', 'لحفلات بطابع هادئ وراقي، نوفر لك خدمة تنظيم متكاملة لحفلات الزفاف البسيطة. نعتني بكل التفاصيل من التخطيط وحتى التنسيق، بأسلوب أنيق يعكس ذوقك ويضمن أجواء دافئة ومميزة ليوم لا يُنسى.\r\n\r\n', '12000', 'images/sweddings.jpg'),
(203, 222, 'تنظيم تنظيم حفلات أعياد الميلاد', 'نصنع لحظات سحرية في يومها المميز! نقدم خدمة لتنظيم حفلات أعياد الميلاد للبنات الصغيرات، تشمل ديكورات مبهجة و تنسيقات مميزة.\r\n\r\n', '1200', 'images/birthday.jpg'),
(204, 222, 'باقة حفل تخرج', 'احتفل بإنجازك بأسلوب راق ومميز! نقدم خدمة تنظيم متكاملة لحفلات التخرج تشمل تنسيق الديكور، تنظيم طاولة الطعام، تجهيز التوزيعات، بالإضافة إلى ركن تصوير أنيق يخلد أجمل لحظاتك.\r\n\r\n', '3100', 'images/grad.jpg'),
(301, 333, 'تصوير فوتوغرافي احترافي', 'نوثق لحظاتك بأعلى جودة! نقدم خدمات تصوير فوتوغرافي وفيديو احترافية للمناسبات، تشمل التعديل والمونتاج باحترافية عالية.\r\n\r\n', '4500', 'images/photography.jpg'),
(401, 444, 'تنسيق الزهور والديكور', 'تنسيق زهور فاخر وديكورات مميزة للمناسبات والحفلات بتصاميم إبداعية.', '1400', 'images/fdecor.jpg'),
(402, 444, 'تنسيق بالونات وديكور', 'نحول مناسبتك إلى لوحة فنية! نقدم خدمات تنسيق بالونات وديكور بأفكار مبتكرة ولمسات أنيقة.', '1200', 'images/bdecor.jpg'),
(501, 555, 'قصر زواج فاخر', 'استأجر واستمتع بأجواء راقية في قصر زواج فاخر مصمم بأعلى معايير الفخامة، ليمنحك تجربة استثنائية في أهم يوم بحياتك.', '20000', 'images/hall.jpg'),
(601, 666, 'فنانة مع فرقة موسيقية', 'فنانة متميزة مع فرقة موسيقية لإحياء المناسبات والأفراح بأجواء طربية.', '5000', 'images/mic.jpeg'),
(602, 666, 'عازف عود للمناسبات', 'عازف عود محترف لإضفاء أجواء طربية في المناسبات الخاصة.', '2000', 'images/oud.jpg'),
(701, 777, 'ضيافة مع صبابات وعاملات', 'ضيافة تشمل العديد من الخيارات مع صبابة وعاملات محترفات للتقديم للضيوف بأناقة وتنظيم.', '1050', 'images/sababa.jpg'),
(801, 888, 'كوشة عروس فاخرة', 'تصميم وتنفيذ كوشات أعراس فاخرة بأحدث التصاميم العصرية', '8000', 'images/kosha.jpg'),
(802, 888, 'تنظيم استقبال مولود', 'نساعدك في استقبال مولودك الجديد بأجمل طريقة! نقدم خدمة تنظيم متكاملة لاستقبال المواليد تشمل تنسيق الديكور، الورد، وتوزيعات مبتكرة.\r\n\r\n', '6000', 'images/breception.jpg'),
(901, 999, 'باقة تقديمات حالي ومالح', 'باقة متكاملة مكونة من 16 صينية متنوعة، مع حرية اختيار الأصناف بين الحلويات والموالح حسب ذوق العميل.', '4800', 'images/taqdimat.jpg'),
(902, 999, 'باقة تقديمات حلا', 'باقة حلا فاخرة تتكون من 5 صواني متنوعة بتنسيق أنيق، مثالية للمناسبات الخاصة. تشمل إمكانية الكتابة على الشوكولاتة لإضافة لمسة شخصية.\r\n\r\n', '820', 'images/sweets.jpg'),
(903, 999, 'صينية حلا بطابع رمضاني', 'تشكيلة حلا فاخرة بتنسيق مستوحى من أجواء رمضان، مثالية للتقديم في السهرات الرمضانية.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '310', 'images/ramadan.jpg'),
(1001, 1010, 'باقة العشاء المتكاملة', 'وجبة عشاء متكاملة تكفي مئة شخص تجمع بين الطعم الشهي والتنسيق الأنيق. تضم مجموعة مختارة من الأطباق الرئيسية والمقبلات والحلويات بتقديم راقي.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '7300', 'images/buffet.jpg'),
(1002, 1010, 'بوفيه رمضاني', 'أجواء رمضانية بطابع مميز! بوفيه متكامل يضم تشكيلة من الأطباق الرمضانية التقليدية، يشمل المقبلات، الأطباق الرئيسية، والحلويات. يكفي ل12-15 شخص.', '1000', 'images/ramadan2.jpg'),
(1007, 222, 'تنظيم غبقة رمضانية', 'نقدم لك خدمة تنظيم غبقات رمضانية بطابع تقليدي وأنيق، تشمل تنسيق الديكور، طاولة الضيافة، ركن التصوير، وتقديمات رمضانية مميزة تضيف دفء وجمال لأمسياتك الرمضانية.', '3700', 'uploads/img_67e4ed7eb6d7c6.20817922.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `servicecategories`
--

CREATE TABLE `servicecategories` (
  `serviceID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `servicecategories`
--

INSERT INTO `servicecategories` (`serviceID`, `categoryID`) VALUES
(101, 2),
(101, 3),
(101, 4),
(101, 8),
(102, 2),
(102, 3),
(102, 4),
(102, 8),
(103, 2),
(103, 3),
(103, 4),
(103, 8),
(201, 3),
(201, 9),
(201, 12),
(202, 3),
(202, 9),
(202, 12),
(203, 5),
(203, 9),
(203, 12),
(204, 1),
(204, 9),
(204, 12),
(301, 1),
(301, 3),
(301, 5),
(301, 6),
(401, 1),
(401, 2),
(401, 4),
(401, 5),
(401, 6),
(401, 9),
(402, 1),
(402, 2),
(402, 4),
(402, 5),
(402, 6),
(402, 9),
(501, 1),
(501, 3),
(601, 1),
(601, 2),
(601, 3),
(601, 10),
(602, 2),
(602, 3),
(602, 4),
(602, 10),
(701, 1),
(701, 2),
(701, 4),
(701, 6),
(701, 7),
(701, 11),
(801, 3),
(801, 9),
(801, 12),
(802, 6),
(802, 9),
(802, 12),
(901, 1),
(901, 2),
(901, 3),
(901, 4),
(901, 6),
(901, 7),
(902, 1),
(902, 2),
(902, 3),
(902, 4),
(902, 6),
(902, 7),
(903, 4),
(903, 7),
(1001, 1),
(1001, 2),
(1001, 3),
(1001, 8),
(1002, 4),
(1002, 8),
(1007, 4),
(1007, 7),
(1007, 9),
(1007, 12);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('customer','vendor') NOT NULL,
  `phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `email`, `password`, `role`, `phone`) VALUES
(11, 'Sara_moh', 'Sara_mo@gmail.com', '$2y$10$X0ZiAYFTUqBc/yIxOwSFPOY8Fgq.L/imrDTRXsz/3Ye43OVAKtr6W', 'customer', '0537499985'),
(12, 'munira_sultan', 'munira_sultan@gmail.com', '$2y$10$WNpFyv5wmsAlXT8uc0o/WeDrZamKTts1SnOJ6guqPdroM2Q4sW92.', 'customer', '0547659879'),
(13, 'hessah_salem', 'hessah_salem@hotmail.com', '$2y$10$S7LMCgwJMlu0dv03i3s.ZeAOxgCH5VsR224mw/KzO8xmMHVxdBx5a', 'customer', '0567654329'),
(111, 'azima_kitchen', 'azima@planperfect.com', '$2y$10$khDGEX7Zzk2hfvFWUzuR.ee/EzjlK39/kmtILnVN0j26H15WTlnJa', 'vendor', '0505156673'),
(222, 'rawaa_events', 'rawaa@planperfect.com', '$2y$10$9g1UupGnJpkIPB7JMZpXr.viw1Y1GjgBng0MrewWc601dxKJhnMMG', 'vendor', '0505322121'),
(333, 'lahdhat_photography', 'lahdhat@planperfect.com', '$2y$10$wxuWrnV/1l.oBprPlJ1av./JZcVlFLhGo9PD4lUibsslL.tr2gzce', 'vendor', '0534457639'),
(444, 'jasmine_flowers', 'jasmine_flowers@planperfect.com', '$2y$10$YC4cOz82ADfNJULJtPkCRud3Fkb9tY10eBoWQnn4D..smAS0nSUkm', 'vendor', '0504667893'),
(555, 'happy_palace', 'happy_palace@planperfect.com', '$2y$10$WsG5bT.IGNDvULMvcE1s.urD0aTiH9eiAZxvewOkWOxQA0hUo48Fm', 'vendor', '0534678992'),
(666, 'nagham_alkhalij', 'nagham_alkhalij@planperfect.com', '$2y$10$VEAajDHPXAEqz0uFFAaZNutauoHR3tvXYvqMFoKHoNXkok3EQraZW', 'vendor', '0505674339'),
(777, 'asalat_aldiyafa', 'asalat_aldiyafa@planperfect.com', '$2y$10$PEyIHW4UDbmN2bthIRG18.QP5HkJePKb1sZcjiVHZ/dPp6sRRK97O', 'vendor', '0534478895'),
(888, 'creative_touch', 'creative_touch@planperfect.com', '$2y$10$Vguejs9yDETP9p9RON32feQVOiaO9R07oPLl89HCAAwlMYuwWcKXy', 'vendor', '0546789945'),
(999, 'happy_sweets', 'happy_sweets@planperfect.com', '$2y$10$9pX61G3nPLs1mKarkScYuupnB8PchgeGGa9YhW3QG4uWpamJTM0yi', 'vendor', '0506789902'),
(1010, 'maram_kitchen', 'maram_kitchen@planperfect.com', '$2y$10$Q9lt/lwPKrRmy6YWyw3oq.SUYkXK1DAZGKh7NJwERMnJDGKlHMWQy', 'vendor', '0503789904');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendorID` int(11) NOT NULL,
  `businessname` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendorID`, `businessname`, `address`) VALUES
(111, 'عزيمة كيتشن', 'الرياض'),
(222, 'رواء لتنظيم المناسبات', 'الرياض'),
(333, 'لحظات للتصوير', 'الرياض'),
(444, 'أزهار الياسمين', 'الرياض'),
(555, 'قصر الأفراح', 'الرياض'),
(666, 'نغم الخليج', 'الرياض'),
(777, 'أصالة الضيافة', 'الرياض'),
(888, 'لمسة الإبداع', 'الرياض'),
(999, 'حلويات السعادة', 'الرياض'),
(1010, 'الشيف مرام', 'الرياض');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favoriteID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`serviceID`),
  ADD KEY `vendorID` (`vendorID`);

--
-- Indexes for table `servicecategories`
--
ALTER TABLE `servicecategories`
  ADD PRIMARY KEY (`serviceID`,`categoryID`),
  ADD KEY `serviceID` (`serviceID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `userID` (`userID`),
  ADD KEY `userID_2` (`userID`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendorID`),
  ADD KEY `vendorID` (`vendorID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favoriteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `favorites_ibfk_3` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_3` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`),
  ADD CONSTRAINT `orderdetails_ibfk_4` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`vendorID`) REFERENCES `vendor` (`vendorID`);

--
-- Constraints for table `servicecategories`
--
ALTER TABLE `servicecategories`
  ADD CONSTRAINT `servicecategories_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`),
  ADD CONSTRAINT `servicecategories_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`);

--
-- Constraints for table `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `fk_vendor_user` FOREIGN KEY (`vendorID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `vendor_ibfk_1` FOREIGN KEY (`vendorID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
