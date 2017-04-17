ALTER TABLE `submissions_rejected`
ADD COLUMN `created_at` DATETIME NOT NULL AFTER `submitted`,
ADD COLUMN `updated_at` DATETIME NOT NULL AFTER `created_at`;
ALTER TABLE `submissions_archive`
ADD COLUMN `created_at` DATETIME NOT NULL AFTER `review_comments`,
ADD COLUMN `updated_at` DATETIME NOT NULL AFTER `created_at`;
