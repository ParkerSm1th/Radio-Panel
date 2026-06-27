SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `nav_ranks`;
TRUNCATE TABLE `panel_pages`;
TRUNCATE TABLE `users`;
INSERT INTO `nav_ranks` (`id`, `name`, `prefix`, `permRole`, `icon`, `dev`, `radio`, `media`, `social`, `class`) VALUES
(1, 'Staff', 'Staff', 1, 'fa fa-user', 0, 0, 0, 0, 'dstaff'),
(2, 'Radio', 'Radio', 1, 'fa fa-microphone', 0, 1, 0, 0, 'dstaff'),
(3, 'Head DJ', 'HDJ', 2, 'fa fa-headphones', 0, 1, 0, 0, 'sstaff'),
(4, 'Media', 'Media', 1, 'fa fa-newspaper', 0, 0, 1, 0, 'dstaff'),
(5, 'Social', 'Social', 1, 'fa fa-share-alt', 0, 0, 0, 1, 'dstaff'),
(6, 'Manager', 'Manager', 3, 'fa fa-users-cog', 0, 0, 0, 0, 'manager'),
(7, 'Administration', 'Admin', 4, 'fa fa-key', 0, 0, 0, 0, 'admin'),
(8, 'Development', 'Dev', 1, 'fa fa-code', 1, 0, 0, 0, 'owner');

INSERT INTO `panel_pages` (`name`, `nav_rank`, `url`, `position`, `dev`, `pending`, `redirect`) VALUES
('Dashboard', 1, 'Staff.Dashboard', 1, 0, 0, 0),
('Profile', 1, 'Staff.Profile', 2, 0, 0, 0),
('Change Password', 1, 'Staff.ChangePass', 3, 0, 0, 0),
('Staff Points', 1, 'Staff.Points', 4, 0, 0, 0),
('Your Reviews', 1, 'Staff.Reviews', 5, 0, 0, 0),
('Rules', 1, 'Staff.Rules', 6, 0, 0, 0),
('Staff Contact List', 1, 'Staff.Contact', 7, 0, 0, 0),
('Post Away', 1, 'Staff.PostAway', 8, 0, 0, 0),
('Logout', 1, 'Staff.Logout', 9, 0, 0, 0),
('LogoutFast', 1, 'Staff.LogoutFast', 10, 0, 0, 0),
('Connection Information', 2, 'Radio.Connection', 1, 0, 0, 0),
('Timetable', 2, 'Radio.Timetable', 2, 0, 0, 0),
('Your Slots', 2, 'Radio.Slots', 3, 0, 0, 0),
('Streamer', 2, 'Radio.Streamer', 4, 0, 0, 0),
('DJ Says', 2, 'Radio.DJSays', 5, 0, 0, 0),
('Like Leaderboard', 2, 'Radio.Likes', 6, 0, 0, 0),
('Radio Resources', 2, 'Radio.Resources', 7, 0, 0, 0),
('Radio Rules', 2, 'Radio.Rules', 8, 0, 0, 0),
('Banned Songs', 2, 'Radio.Banned', 9, 0, 0, 0),
('Add Point', 3, 'HDJ.AddPoint', 1, 0, 0, 0),
('Booking Logs', 3, 'HDJ.Booking', 2, 0, 0, 0),
('Panel Made Messages', 3, 'HDJ.Messages', 3, 0, 0, 0),
('New Review', 3, 'HDJ.NewReview', 4, 0, 0, 0),
('Point History', 3, 'HDJ.PointHistory', 5, 0, 0, 0),
('Post Away Requests', 3, 'HDJ.PostAway', 6, 0, 0, 0),
('Posted Away Users', 3, 'HDJ.PostedAway', 7, 0, 0, 0),
('Reputation', 3, 'HDJ.Reputation', 8, 0, 0, 0),
('All Requests', 3, 'HDJ.Requests', 9, 0, 0, 0),
('Reviews Due', 3, 'HDJ.Reviews', 10, 0, 0, 0),
('Slacking DJs', 3, 'HDJ.Slacking', 11, 0, 0, 0),
('Song Log', 3, 'HDJ.SongLog', 12, 0, 0, 0),
('Post Away Request', 3, 'HDJ.ViewPA', 13, 0, 0, 0),
('View Review', 3, 'HDJ.ViewReview', 14, 0, 0, 0),
('Rules', 4, 'Media.Rules', 1, 0, 0, 0),
('Twitter', 5, 'Social.Twitter', 1, 1, 0, 0),
('Applications', 6, 'Manager.Applications', 1, 0, 0, 0),
('Auto DJ Says', 6, 'Manager.AutoDJSays', 2, 0, 0, 0),
('Edit Connection Info', 6, 'Manager.Connection', 3, 0, 0, 0),
('Delete User', 6, 'Manager.DeleteUser', 4, 0, 0, 0),
('Edit User', 6, 'Manager.EditUser', 5, 0, 0, 0),
('New Staff Member', 6, 'Manager.New', 6, 0, 0, 0),
('New Short URL', 6, 'Manager.NewShort', 7, 0, 0, 0),
('Review History', 6, 'Manager.ReviewHistory', 8, 0, 0, 0),
('Short URLs', 6, 'Manager.Short', 9, 0, 0, 0),
('Radio Spy', 6, 'Manager.Spy', 10, 0, 0, 0),
('Manage Staff', 6, 'Manager.Staff', 11, 0, 0, 0),
('Manage Trialists', 6, 'Manager.Trialists', 12, 0, 0, 0),
('User Actions', 6, 'Manager.UserActions', 13, 0, 0, 0),
('View Application', 6, 'Manager.ViewApplication', 14, 0, 0, 0),
('Active Users', 7, 'Admin.ActiveUsers', 1, 0, 0, 0),
('Assign Reviews', 7, 'Admin.AssignReviews', 2, 0, 0, 0),
('Ban a Song', 7, 'Admin.BanSong', 3, 0, 0, 0),
('Banned IPs', 7, 'Admin.Banned', 4, 0, 0, 0),
('Banned Songs', 7, 'Admin.BannedSongs', 5, 0, 0, 0),
('Booking Logs', 7, 'Admin.Booking', 6, 0, 0, 0),
('Edit Perm Show', 7, 'Admin.EditPerm', 7, 0, 0, 0),
('Panel Logs', 7, 'Admin.Logs', 8, 0, 0, 0),
('New Perm Show', 7, 'Admin.NewShow', 9, 0, 0, 0),
('Publish Reviews', 7, 'Admin.PublishReviews', 10, 0, 0, 0),
('Review Management', 7, 'Admin.Reviews', 11, 0, 0, 0),
('Perm Shows', 7, 'Admin.Shows', 12, 0, 0, 0),
('Submitted Reviews', 7, 'Admin.SubmittedReviews', 13, 0, 0, 0),
('Dev Actions', 8, 'Dev.Actions', 1, 1, 0, 0),
('Active Users', 8, 'Dev.ActiveUsers', 2, 1, 0, 0),
('Panel Logs', 8, 'Dev.Logs', 3, 1, 0, 0),
('New Page', 8, 'Dev.NewPage', 4, 1, 0, 0),
('Manage Pages', 8, 'Dev.Pages', 5, 1, 0, 0),
('Testing', 8, 'Dev.Testing', 6, 1, 0, 0);

INSERT INTO `users` (
  `id`, `username`, `pass`, `avatarURL`, `permRole`, `displayRole`, `radio`, `media`, `social`, `developer`,
  `pending`, `guest`, `type`, `lastLogin`, `lastLoginIP`, `newIP`, `inactive`, `hired`, `region`, `discord`,
  `discord_id`, `trial`, `djSays`, `bio`, `viewed_info`
) VALUES (
  1, 'admin', '$2y$10$slDxAzVLBntmIavVy4NkjuZjGL/kYsAjX/G8emli0dRBQfUcpJxX.', 'default/default.png', 6, 'Owner', '1', '1', '1', '1',
  0, '0', 0, '', '', '', 'false', '', 'Global', '1', '', '0', '', '', 1
);

SET FOREIGN_KEY_CHECKS = 1;
