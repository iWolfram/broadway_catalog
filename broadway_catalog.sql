-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2017 at 11:11 AM
-- Server version: 5.5.56-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rresuena1_2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `broadway_catalog`
--

CREATE TABLE `broadway_catalog` (
  `showid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `premiereDate` date DEFAULT NULL,
  `edmontonStartDate` date DEFAULT NULL,
  `edmontonEndDate` date DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `videourl` varchar(255) DEFAULT NULL,
  `videoid` varchar(255) DEFAULT NULL,
  `musicby` varchar(255) DEFAULT NULL,
  `lyricsby` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `broadway_catalog`
--

INSERT INTO `broadway_catalog` (`showid`, `title`, `description`, `premiereDate`, `edmontonStartDate`, `edmontonEndDate`, `filename`, `videourl`, `videoid`, `musicby`, `lyricsby`) VALUES
(1, 'Jersey Boys', 'Jersey Boys is a musical biography of the Four Seasonsâ€”the rise, the tough times and personal clashes, and the ultimate triumph of a group of friends whose music became symbolic of a generation. Far from a mere tribute concert (though it does include numbers from the popular Four Seasons songbook), Jersey Boys gets to the heart of the relationships at the center of the groupâ€”with a special focus on frontman Frankie Valli, the small kid with the big falsetto. In addition to following the quartetâ€™s coming of age as performers, the core of the show is how an allegiance to a code of honor learned in the streets of their native New Jersey got them through a multitude of challenges: gambling debts, Mafia threats and family disasters. Jersey Boys is a glimpse at the people behind a sound that has managed to endure for over four decades in the hearts of the public.', '2005-11-06', '2017-11-24', '2017-11-26', '5a1d0a69da1109.82079887', 'https://www.youtube.com/watch?v=Sbtmf1_V-mE', 'Sbtmf1_V-mE', 'Bob Gaudio', 'Bob Crewe'),
(2, 'Wicked', 'Long before that girl from Kansas arrives in Munchkinland, two girls meet in the land of Oz. Oneâ€”born with emerald green skinâ€”is smart, fiery and misunderstood. The other is beautiful, ambitious and very popular. How these two grow to become the Wicked Witch of the West and Glinda the Good makes for \"the most completeâ€”and completely satisfyingâ€”new musical in a long time\" (USA Today).', '2003-05-28', '2014-07-02', '2014-07-20', '5a1d0f42d39bc1.62339672', 'https://www.youtube.com/watch?v=nrpLayS57cY', 'nrpLayS57cY', 'Stephen Schwartz', 'Stephen Schwartz'),
(6, 'The Book of Mormon', 'The Book of Mormon is a musical comedy about two young Mormon missionaries who travel to Africa to preach the Mormon religion. First staged in 2011, the play satirizes various Mormon beliefs and practices. The script, lyrics, and music were written by Trey Parker, Robert Lopez, and Matt Stone.[1] Parker and Stone were best known for creating the animated comedy South Park; Lopez had co-written the music for the musical Avenue Q.', '2011-03-24', '2015-03-24', '2015-03-29', '5a216d0be513a0.14026735', 'https://www.youtube.com/watch?v=k4-eWO1xu3I', 'k4-eWO1xu3I', 'Trey Parker, Robert Lopez & Matt Stone', 'Trey Parker, Robert Lopez & Matt Stone'),
(7, 'test insert', 'asdlfkjasdlkfjasdlkj', '2017-12-06', '0000-00-00', '0000-00-00', '5a21769ea2cf25.46296729', 'https://www.youtube.com/watch?v=6ZfuNTqbHE8', '6ZfuNTqbHE8', 'asdlfkjasdlkfj', 'asldkfjasdlfkj');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `broadway_catalog`
--
ALTER TABLE `broadway_catalog`
  ADD PRIMARY KEY (`showid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `broadway_catalog`
--
ALTER TABLE `broadway_catalog`
  MODIFY `showid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
