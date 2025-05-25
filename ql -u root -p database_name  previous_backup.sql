[33mcommit 7e5124d5d776710762b3a98cbae3edbfc936c136[m
Author: Oyaon Sultan <oyaon.sultan07@gmail.com>
Date:   Fri May 23 02:35:13 2025 +0600

    Push all changes

[1mdiff --git a/.blackboxrules b/.blackboxrules[m
[1mnew file mode 100644[m
[1mindex 0000000..e69de29[m
[1mdiff --git a/.phpunit.result.cache b/.phpunit.result.cache[m
[1mnew file mode 100644[m
[1mindex 0000000..d5e27af[m
[1m--- /dev/null[m
[1m+++ b/.phpunit.result.cache[m
[36m@@ -0,0 +1 @@[m
[32m+[m[32mC:37:"PHPUnit\Runner\DefaultTestResultCache":616:{a:2:{s:7:"defects";a:6:{s:35:"DatabaseTest::testSingletonInstance";i:4;s:28:"DatabaseTest::testConnection";i:3;s:32:"DatabaseTest::testQueryExecution";i:4;s:38:"UserTest::testUserNotLoggedInInitially";i:4;s:33:"UserTest::testLoadUserByIdInvalid";i:4;s:36:"UserTest::testLoadUserByEmailInvalid";i:4;}s:5:"times";a:6:{s:35:"DatabaseTest::testSingletonInstance";d:0.028;s:28:"DatabaseTest::testConnection";d:0.068;s:32:"DatabaseTest::testQueryExecution";d:0.004;s:38:"UserTest::testUserNotLoggedInInitially";d:0.029;s:33:"UserTest::testLoadUserByIdInvalid";d:0.002;s:36:"UserTest::testLoadUserByEmailInvalid";d:0.001;}}}[m
\ No newline at end of file[m
[1mdiff --git a/ROADMAP.md b/ROADMAP.md[m
[1mindex 7334619..edfe6b9 100644[m
[1m--- a/ROADMAP.md[m
[1m+++ b/ROADMAP.md[m
[36m@@ -1,5 +1,17 @@[m
 # Library Management System (LMS) Migration Roadmap[m
 [m
[32m+[m[32m**Project Status Summary (as of May 22, 2025):**[m
[32m+[m[32m- Database schema and migration script: ✅ Done[m
[32m+[m[32m- Backend core classes: ✅ Done[m
[32m+[m[32m- Refactoring, error handling, logging: ⬜ In Progress / Missing[m
[32m+[m[32m- Security enhancements: ⬜ Missing[m
[32m+[m[32m- Frontend improvements: ⬜ Missing[m
[32m+[m[32m- Feature enhancements: ⬜ Missing[m
[32m+[m[32m- Testing & deployment: ⬜ Missing[m
[32m+[m[32m- Documentation & training: ⬜ Missing[m
[32m+[m
[32m+[m[32m---[m
[32m+[m
 This document outlines the plan for migrating the existing LMS application to a more robust, secure, and maintainable architecture.[m
 [m
 ## Phase 1 - Database Migration (Week 1)[m
[36m@@ -11,6 +23,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Document database structure[m
 [m
 ### Implementation Steps[m
[32m+[m
 1. Run the migration script: `php database/migrate.php`[m
 2. Verify data integrity after migration[m
 3. Create regular backup schedule[m
[36m@@ -29,6 +42,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Add logging system[m
 [m
 ### Implementation Steps for Backend[m
[32m+[m
 1. Update each page to use the new class structure[m
 2. Test each functionality after refactoring[m
 3. Implement comprehensive error handling[m
[36m@@ -44,6 +58,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Add rate limiting for login attempts[m
 [m
 ### Implementation Steps for Security[m
[32m+[m
 1. Update user authentication system[m
 2. Add CSRF tokens to all forms[m
 3. Implement input validation for all user inputs[m
[36m@@ -60,6 +75,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Enhance accessibility[m
 [m
 ### Implementation Steps for Frontend[m
[32m+[m
 1. Update templates to use modern Bootstrap features[m
 2. Create component-based structure[m
 3. Implement JavaScript validation[m
[36m@@ -76,6 +92,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Enhance admin dashboard[m
 [m
 ### Implementation Steps for Features[m
[32m+[m
 1. Develop each feature individually[m
 2. Test thoroughly before integration[m
 3. Document new features for users[m
[36m@@ -90,6 +107,7 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Document deployment process[m
 [m
 ### Implementation Steps for Testing[m
[32m+[m
 1. Create test cases for all functionality[m
 2. Perform security audit[m
 3. Deploy to staging environment[m
[36m@@ -104,14 +122,18 @@[m [mThis document outlines the plan for migrating the existing LMS application to a[m
 - [ ] Conduct training sessions[m
 - [ ] Create video tutorials[m
 [m
[31m-### Implementation Steps for Documentation[m
[31m-1. Document all features and functionality[m
[31m-2. Create training materials[m
[31m-3. Conduct training sessions for users and administrators[m
[32m+[m[32m### Implementation Steps for Documentation and Training[m
[32m+[m
[32m+[m[32m1. Document all features and functionality in user, admin, and developer guides[m
[32m+[m[32m2. Create training materials (slides, handouts, FAQs)[m
[32m+[m[32m3. Record and edit video tutorials for key workflows[m
[32m+[m[32m4. Schedule and conduct training sessions for users and administrators[m
[32m+[m[32m5. Gather feedback and update documentation as needed[m
 [m
 ## Migration Strategy[m
 [m
 ### Database Migration Step[m
[32m+[m
 Run the migration script to create the new database structure and migrate existing data.[m
 [m
 ```bash[m
[36m@@ -119,6 +141,7 @@[m [mphp database/migrate.php[m
 ```[m
 [m
 ### Code Refactoring Step[m
[32m+[m
 Update each page to use the new class structure:[m
 [m
 1. Replace direct database connections with the Database class[m
[36m@@ -128,9 +151,11 @@[m [mUpdate each page to use the new class structure:[m
 5. Update cart and payment to use the Cart class[m
 [m
 ### Testing Step[m
[32m+[m
 Test each functionality after refactoring to ensure everything works as expected.[m
 [m
 ### Deployment Step[m
[32m+[m
 Deploy the updated application to production.[m
 [m
 ## Rollback Plan[m
[1mdiff --git a/about.php b/about.php[m
[1mnew file mode 100644[m
[1mindex 0000000..2da15d0[m
[1m--- /dev/null[m
[1m+++ b/about.php[m
[36m@@ -0,0 +1,6 @@[m
[32m+[m[32m<?php require_once "includes/init.php"; ?>[m
[32m+[m[32m<?php include ("header.php"); ?>[m
[32m+[m
[32m+[m[32m<!-- Existing code for the page goes here -->[m
[32m+[m
[32m+[m[32m<?php include ("footer.php"); ?>[m
\ No newline at end of file[m
[1mdiff --git a/actions.php b/actions.php[m
[1mindex 872f948..2f59c9a 100644[m
[1m--- a/actions.php[m
[1m+++ b/actions.php[m
[36m@@ -1,32 +1,33 @@[m
 <?php[m
[31m-require_once("./admin/db-connect.php");[m
[32m+[m[32mrequire_once 'includes/init.php';[m
 [m
 if(isset($_GET["a"])){[m
[31m-	extract($_GET);[m
[32m+[m[32m    extract($_GET);[m
 [m
[31m-	if($a == "reissue"){[m
[31m-		$query = $conn->query("UPDATE `borrow_history` SET `status`='Requested' WHERE `id`=$t ;");[m
[31m-		if($query){ ?>[m
[31m-			<script type="text/javascript">[m
[31m-				alert("Re-issued successfully");[m
[31m-				window.history.go(-1);[m
[31m-			</script>[m
[31m-		<?php }[m
[31m-	}[m
[31m-	else if($a == "delete"){[m
[31m-		$query = $conn->query("DELETE FROM `borrow_history` WHERE `id`=$t ;");[m
[31m-		if($query){ [m
[31m-			if($conn->query("UPDATE `all_books` SET `quantity`=`quantity`+1 WHERE `id`=$book_id ;")){ ?>[m
[31m-				<script type="text/javascript">[m
[31m-					alert("Request deleted successfully");[m
[31m-					window.history.go(-1);[m
[31m-				</script>[m
[31m-			<?php }[m
[31m-		}[m
[31m-	}[m
[32m+[m[32m    if($a == "reissue"){[m
[32m+[m[32m        $query = $db->query("UPDATE `borrow_history` SET `status`='Requested' WHERE `id`=?", "i", [$t]);[m
[32m+[m[32m        if($query){ ?>[m
[32m+[m[32m            <script type="text/javascript">[m
[32m+[m[32m                alert("Re-issued successfully");[m
[32m+[m[32m                window.history.go(-1);[m
[32m+[m[32m            </script>[m
[32m+[m[32m        <?php }[m
[32m+[m[32m    }[m
[32m+[m[32m    else if($a == "delete"){[m
[32m+[m[32m        $query = $db->query("DELETE FROM `borrow_history` WHERE `id`=?", "i", [$t]);[m
[32m+[m[32m        if($query){[m[41m [m
[32m+[m[32m            $updateQuery = $db->query("U