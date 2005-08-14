<?
$topic = Import ("topic", "GP");

?>
<div class="doc_nav">
<a class="doc_nav_lnk" href="javascript:history.go(-1)">Back</a> &nbsp;
<?
if ($topic != "Proms") {
	?><a class="doc_nav_lnk" href="<?=$PHP_SELF?>?action=Documentation&topic=Proms">Index</a> &nbsp;<?
}
?>
</div>
<div class="doc_body">
<?

switch ($topic) {
	case "Proms"             : Proms(); break;

	case "AccountList"       : Accountlist(); break;
	case "AccountOverview"   : AccountOverview(); break;
	case "AccountLogin"      : AccountLogin(); break;
	case "AccountCreate"     : AccountCreate(); break;
	case "AccountMod"        : AccountMod(); break;
	
	case "BugList"           : BugList(); break;
	case "BugOverview"       : BugOverview(); break;
	case "BugAdd"            : BugAdd(); break;
	case "BugMod"            : BugMod(); break;
	
	case "ForumTopicList"    : ForumTopicList(); break;
	case "ForumTopicAdd"     : ForumTopicAdd(); break;
	case "ForumTopicMod"     : ForumTopicMod(); break;
	
	case "ForumView"         : ForumView(); break;
	case "ForumReply"        : ForumReply(); break;
	
	case "ProjectList"       : ProjectList(); break;
	case "ProjectOverview"   : ProjectOverview(); break;
	case "ProjectMod"        : ProjectModify(); break;
	case "ProjectAdd"        : ProjectAdd(); break;
	
	case "ProjectMemberList" : ProjectMemberList(); break;
	case "ProjectMemberAdd"  : ProjectMemberAdd(); break;
	case "ProjectMemberMod"  : ProjectMemberMod(); break;
	
	case "ProjectPartList"   : ProjectPartList(); break;
	case "ProjectPartAdd"    : ProjectPartAdd(); break;
	case "ProjectPartMod"    : ProjectPartMod(); break;
	
	case "ReleaseList"       : ReleaseList(); break;
	case "ReleaseMod"        : ReleaseMod(); break;
	case "ReleaseOverview"   : ReleaseOverview(); break;
	case "ReleaseAdd"        : ReleaseAdd(); break;
	
	case "TodoList"          : TodoList(); break;
	case "TodoOverview"      : TodoOverview(); break;
	case "TodoAdd"           : TodoAdd(); break;
	case "TodoMod"           : TodoMod(); break;

	case "FileBrowse"         : FileBrowse(); break;
	case "FileList"           : FileList(); break;
	case "FileAdd"            : FileAdd(); break;
	case "FileMod"            : FileMod(); break;

	case "FileCategoryList"   : FileCategoryList(); break;
	case "FileCategoryAdd"    : FileCategoryAdd(); break;
	case "FileCategoryMod"    : FileCategoryMod(); break;

	default                  : Unknown(); break;
}
?>
</div>
<div class="doc_nav_bottom">
<a class="doc_nav_lnk" href="javascript:void(0);" OnClick="window.close()">Close</a>
</div>
<?

function Proms() {
	global $module_title;
	?>
	<h1>PROMS</h1>
	<h2>Project Management System</h2>
	<p>PROMS is a web-based project management system whose primary goal is to provide a facility for developers to communicate amongst other developers, communicating things to end-users as well as getting feed-back from users (in the form of bugs, etc) and as a placeholder for project information. It allows users to see project details, report bugs, feature requests and to discuss projects in a forum. Projects can be split up into multiple parts and project maintainers can assign tasks and programmers to each part. Proms is meant to be a very lightweight replacement of the sourceforge project management software </p>
	<p>This is the documentation for PROMS. You can access the appropriate page for each section of Proms by clicking the <a class="action">?</a> button at the top-right of the screen in the specific section. The manual consists out of these pages:</p>
	<div style="margin-left: 10px;">
		<b>Projects</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectList"><?=$module_title["ProjectList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectOverview"><?=$module_title["ProjectOverview"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectMod"><?=$module_title["ProjectMod"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectAdd"><?=$module_title["ProjectAdd"]?></a><br>
			<b>Members</b>
			<div style="margin-left: 40px;">
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectMemberList"><?=$module_title["ProjectMemberList"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectMemberAdd"><?=$module_title["ProjectMemberAdd"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectMemberMod"><?=$module_title["ProjectMemberMod"]?></a><br>
			</div>
			<b>Parts</b>
			<div style="margin-left: 40px;">
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectPartList"><?=$module_title["ProjectPartList"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectPartAdd"><?=$module_title["ProjectPartAdd"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ProjectPartMod"><?=$module_title["ProjectPartMod"]?></a><br>
			</div>
		</div>
		<b>Releases</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ReleaseList"><?=$module_title["ReleaseList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ReleaseOverview"><?=$module_title["ReleaseOverview"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ReleaseAdd"><?=$module_title["ReleaseAdd"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=ReleaseMod"><?=$module_title["ReleaseMod"]?></a><br>
		</div>
		<b>Files</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileList"><?=$module_title["FileList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileAdd"><?=$module_title["FileAdd"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileMod"><?=$module_title["FileMod"]?></a><br>
			<b>Categories</b>
			<div style="margin-left: 40px;">
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileCategoryList"><?=$module_title["FileCategoryList"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileCategoryAdd"><?=$module_title["FileCategoryAdd"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=FileCategoryMod"><?=$module_title["FileCategoryMod"]?></a><br>
			</div>
		</div>
		<b>Bugs</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=BugList"><?=$module_title["BugList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=BugOverview"><?=$module_title["BugOverview"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=BugAdd"><?=$module_title["BugAdd"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=BugMod"><?=$module_title["BugMod"]?></a><br>
		</div>
		<b>Todos</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=TodoList"><?=$module_title["TodoList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=TodoOverview"><?=$module_title["TodoOverview"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=TodoAdd"><?=$module_title["TodoAdd"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=TodoMod"><?=$module_title["TodoMod"]?></a><br>
		</div>
		<b>Discussions</b>
		<div style="margin-left: 40px;">
			<b>Topics</b>
			<div style="margin-left: 40px;">
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ForumTopicList"><?=$module_title["ForumTopicList"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ForumTopicAdd"><?=$module_title["ForumTopicAdd"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ForumTopicMod"><?=$module_title["ForumTopicMod"]?></a><br>
			</div>
			<b>Threads</b>
			<div style="margin-left: 40px;">
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ForumView"><?=$module_title["ForumView"]?></a><br>
				<a href="<?=$PHP_SELF?>?action=Documentation&topic=ForumReply"><?=$module_title["ForumReply"]?></a><br>
			</div>
		</div>
		<b>Accounts</b>
		<div style="margin-left: 40px;">
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=AccountList"><?=$module_title["AccountList"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=AccountOverview"><?=$module_title["AccountOverview"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=AccountLogin"><?=$module_title["AccountLogin"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=AccountCreate"><?=$module_title["AccountCreate"]?></a><br>
			<a href="<?=$PHP_SELF?>?action=Documentation&topic=AccountMod"><?=$module_title["AccountMod"]?></a><br>
		</div>
	</div>
	<?
}

function Unknown() {
	?>
	<h1>Unknown topic</h1>
	<p>This topic does not have an corresponding entry in the manual. If there should be one, please feel free to write one up, and e-mail it to the author.</p>
	<?
}

function ProjectList() {
	?>
	<h1>Project list</h1>
	<p>This page provides a list of all the projects which are being maintained with this Proms version. For each project it shows the project name, the latest released version and the date on which it was released, the progress of the project and three actions for the project.</p>
	<p>The <b>progress</b> bar shows how far the project is coming along. The percentage is determined by the total number of todo's and the todo's which have been marked <i>done</i>. This progress calculation can be overwritten by the project's owner by manually specifying the progress at the project modification page.</p>
	<p><b>Details</b> links allow you to view the details for this project. By clicking on the <b>Project page</b> link, you will be taken to the project's page which contains a detailed overview of all the information concerning the project. You can also click the project's title to get there. <b>Homepage</b> takes you to the project's homepage, which not necessarily has to be the project page. The homepage can contain an appealing advertisement for the project or whatever the project's owner wants it to contain. This project homepage cannot be hosted within Proms, but has to be kept on a different location. The <b>Author</b> link takes you to the author's information page. That will show you all there is to know about the author (and maintainer) of the project, if he hasn't marked his information as private.</p>
	<p>Administrators will have two additional actions available on this page, namely New project and Account list. The <a class="action">New project</a> action takes you to the page from where you can add new projects. <a class="action">Account list</a> shows a list of all currently registered accounts.</p>
	<?
}

function ProjectModify() {
	?>
	<h1>Project modify</h1>
	<p>Here you can modify a project's details. The <b>short name</b> can contain an acronym of your project's full name. If there is no acronym, simply specify the normal project name here. At the <b>full name</b>, you should specify the complete, unabbreviated name of your project. By changing the <b>owner</b> you can transfer the project to another user. If you do this, you will no longer be the project's maintainer. The <b>description</b> gives you the option to tell something about the project's purpose, its goals, etc. It will be the focal point of the project's page. If you want to manually specify the project's <b>progress</b>, you can do so at the progress field. You must specify only a number between 0 and 100. Do not add a '%' character to it. If you specify a negative number, the project's progress will be automatically calculated by Proms by looking at the total number of todo's and the number of todo's which are done. Some projects may require more information for the end-users. By entering the URL of a web page containing extra information at the <b>Homepage</b> field you can direct users to any additional information. Finally you can specify the <b>License</b> which will cover the project. If you want the project to be invisible and inaccessible for users that are not a member of the project, mark the <b>Private</b> checkbox.</p>
	<?
}

function ProjectAdd() {
	?>
	<h1>Project add</h1>
	<p>Here you can add a new project. Creating a new project can be done by using the wizard. This wizard will take you through a few steps that are necessary to get a complete project entry. The wizard consists of 3 steps.</p>
	<h2>Step 1: Project details</h2>
	<p>Step one of the wizard allows you to enter the basic details about your project. The <b>short name</b> can contain an acronym of your project's full name. If there is no acronym, simply specify the normal project name here. At the <b>full name</b>, you should specify the complete, unabbreviated name of your project. By changing the <b>owner</b> you can transfer the project to another user. If you do this, you will no longer be the project's maintainer. The <b>description</b> gives you the option to tell something about the project's purpose, its goals, etc. It will be the focal point of the project's page. If you want to manually specify the project's <b>progress</b>, you can do so at the progress field. You must specify only a number between 0 and 100. Do not add a '%' character to it. If you specify a negative number, the project's progress will be automatically calculated by Proms by looking at the total number of todo's and the number of todo's which are done. Some projects may require more information for the end-users. By entering the URL of a web page containing extra information at the <b>Homepage</b> field you can direct users to any additional information. Finally you can specify the <b>License</b> which will cover the project.  If you want the project to be invisible and inaccessible for users that are not a member of the project, mark the <b>Private</b> checkbox.</p>
	<h2>Step 2: Project parts</h2>
	<p>Step two provides you with the ability to add project parts to your project. Normally, a project is composed out of different parts, for instance a backend and an interface. During this step you can add and modify project parts..</p>
	<h2>Step 3: Discussion topics</h2>
	<p>This step allows you to add various topics for the discussions.</p>
	<?
}

function ProjectOverview() {
	?>
	<h1>Project overview</h1>
	<p>This page gives you, surprise surprise, an overview of a project. All the information needed to get a quick overview of the project is shown here. From here you can get access to everything concerning the project like bugs, todo's, etc.</p>
	<p>First off is a summary of the project's purpose and any other information which the project's maintainer wants you to know about the project. Then some general information is shown about its <b>author</b>, the <b>license</b> covering the project, the <b>progress</b> and its official <b>homepage</b>. By clicking on the <b>owner</b> name, you will be taken to the account's overview page which shows relevant information about this user (if he hasn't marked it private).</p>
	<p>The <b>Latest releases</b> section shows the five last release for this project as well as some quick links to view the release information. By clicking on the <b>version</b> number, you can view all of that releases details. The <b>Changes</b> link takes you the document containing the changes made in that particular release of the project. The rest of the links (<b>Source</b>, <b>Binary</b>, etc) are provided so you can quickly download the appropriate package which contains that release. The <b>All releases</b> link gives access to a list of all the releases ever made for this project. This is also where maintainers can add new releases.</p>
	<p>Following the Latest releases section is the section which provide access to a number of sections. If you wish to receive an announcement every time a new release is made by the maintainer, you may click the <b>Subscribe</b> link. This will put your e-mail address on the release list for the project so you will automatically receive a notice. If you wish to view the bug list for the project, or report a new bug yourself, you can do by clicking on the <b>Bugs</b> link. You will then be taken to the bugs list section. The same goes for the <b>Todo's</b> link, except for the fact that you will probably not be authorized to add new todo's yourself. This is something only administrators, project owners and those who have been given explicit access are allowed to do. Last but not least is the <b>Discuss</b> action, which leads to the discussion forums. There you can pick a topic about which you wish to discuss something, and you can alleviate your hearts desire to blurt out random stuff.</p>
	<p>Project maintainers will have a number of options available to them at the bottom of the screen. If they wish to modify a projects information, they can do so by using the <a class="action">Modify project</a> action button. The <a class="action">Delete project</a> button does just that, it deletes the project. Be careful with that.. it doesn't ask if you are sure. Deleting a project will also delete all information concerning that project, like bugs, forum discussions, etc. The <a class="action">Modify parts</a> allows the maintainer of the project to change the parts out of which the project is made up. These parts are then used in bug reports, todo's, etc. They form a way to subdivide your project into smaller categories. The <a class="action">Modify members</a> gives access to the section where members can be added to the project. Project members can have special rights, like forum maintainer, release maintainer, special todo rights, etc.</p>
	<?
}

function ProjectMemberAdd() {
	?>
	<h1>Project Member Add</h1>
	<p>Adding a project member is really quite simple. The <b>user</b> dropdown box shows all the users currently registered at Proms which aren't already a member of the project. From the list pick the member you wish to add. After that you can give the member any of the <b>rights</b> you want to by selecting them in the list. You can select multiple rights for the member by holding the Control key on your keyboard and clicking the specific rights.</p>
	<?
}

function ProjectMemberMod() {
	?>
	<h1>Project Member Modify</h1>
	<p>When modifying a project member you can choose to revoke or assign rights to that user. You can do so by selecting or de-selecting the appropriate rights in the <b>rights</b> list. You can specify multiple rights to the member by holding the Control key on your keyboard and selecting the rights by clicking on the with the mouse.</p>
	<?
}

function ProjectMemberList() {
	?>
	<h1>Project Member List</h1>
	<p>This page shows the list of members which are assigned to this project. With it, all the rights that were assigned to that member are shown. By clicking on the <a class="action">Modify</a> button, you will be taken to the page which will let you modify that members rights for this specific project. The <a class="action">Delete</a> button removes the user as a member of this project, thereby revoking its rights and privileges.</p>
	<?
}

function ProjectPartAdd() {
	?>
	<h1>Project Part Add</h1>
	<p>To add a project part, enter the <b>title</b> and the <b>description</b> fields.</p>
	<?
}

function ProjectPartMod() {
	?>
	<h1>Project Part Modify</h1>
	<p>Modifying a project part entails nothing more than entering the <b>title</b> and the <b>description</b> for the part.</p>
	<?
}

function ProjectPartList() {
	?>
	<h1>Project Parts List</h1>
	<p>Project parts are very important in Proms. They provide a way to divide a project into multiple smaller pieces. I.e. a file manager, which might have the following parts: 'Configuration', 'Tree', 'File operations', etc. These parts are then used throughout Proms to categorize all kinds of things. For instance, bugs can be reported for each different part. This allows you to manage your project more easily.</p>
	<p>The project parts list shows a list of all the parts of your project, including their <b>descriptions</b>. For each part, two buttons are available. The <a class="action">Modify</a> button takes you to the page where you can make changes to a part's title or description. The <a class="action">Delete</a> button will remove the part. Please make sure that no bugs, todo's or any other things in Proms are registered under this part. Add the bottom of the page you will find a <a class="action">Add part</a> button. This button is for adding new parts to your project.</p>
	<?
}

function AccountList() {
	?>
	<h1>Account list</h1>
	<p>The account list shows a summary of all the accounts which have been created by users within the Proms system. Only the administrator may view this list. From this list, the administrator can choose an account for viewing by clicking on its id number. This will take him to the Account Overview page, from which he can modify the account in question.</p>
	<p>By clicking on the <a class="action">New Account</a> action button he can create a new account.</p>
	<?
}

function AccountLogin() {
	?>
	<h1>Account login</h1>
	<p>This is where you log into Proms. By logging in, you will acquire special privileges that mere visitors don't have. Everybody can have an account. Simply log in by specifying your <b>username</b> and <b>password</b> you entered when signing up for an account. If you have lost your password, you're tough out of luck; there is no way to get it back. You must e-mail the administrator and ask him to reset your accounts password. You'll have to try to prove you are the person to whom the account belongs. If you are lucky, the administrator will change your password for you and then send it to the e-mail address you specified when signing up for the account.</p>
	<p>If you do not already have an account, you can use the <a class="action">Sign up</a> button to acquire an account.</p>
	<?
}

function AccountMod() {
	?>
	<h1>Account Modify</h1>
	<p>This page is for changing your account's details. If you wish to change the <b>password</b>, you must specify it twice. This is so you won't accidentally make a typing mistake in the password field. Please make sure you choose a good random password of at least 6 characters. Use both lowercase and uppercase and mix it with some numbers. This will ensure nobody can guess your password. The password can be up to 20 characters long.</p>
	<p>You can also change your <b>Fullname</b>, <b>email</b> address and your <b>homepage</b>. If you mark the <b>private</b> checkbox, the information you have entered will be kept secret. Other users will not be able to view your details, except for your username and full name.</p>
	<p>The administrator may disable a user's account by checking the <b>Disabled</b> checkbox. The user will then no longer be able to log, in effect banning him.</p>
	<?
}

function AccountCreate() {
	?>
	<h1>Create account</h1>
	<p>A lot of parts of the PROMS system require you to have an account before you can access them. Users can click on the <a class="action">Sign up</a> button at the top right of the screen to create a new account for themselves. Access to the sign-up page can also be found at other places in PROMS.</p>
	<p>To create a new account, you must fill in at least all the fields marked by an Astrix (<b>*</b>). You may choose an appropriate <b>username</b> for yourself which you will have to enter when you wish to log in. The <b>password</b> of your choice must be entered twice to make sure you did not accidentally make a typing error in the password. Please make sure you choose a good random password of at least 6 characters. Use both lowercase and uppercase and mix it with some numbers. This will ensure nobody can guess your password. The password can be up to 20 characters long.</p>
	<p>At the field marked <b>Fullname</b> please enter both your first and lastname. If you absolutely wish to remain anonymous or something like that, you may also enter just your nickname. State your <b>Email</b> address so that the people you communicate with will know how to reach you when they need to. This e-mail address is also used to send you release information (if you choose to sign up for that) and some other opt-in things. Do no worry about receiving spam; all mentioning of the e-mail address within Proms itself is garbled ('f (dot) boender (at) nihilist (dot) nl', for example). If you want to specify your <b>homepage</b> too, do not forget the <i>http://</i> part in front of it.</p>
	<p>Sometimes it is desirable to keep your information private, so other users will not be able to see them. This can be achieved by marking the <b>private</b> checkbox. If you do, people will only be able to view your username and fullname. All other information is kept secret.</p>
	<?
}

function AccountOverview() {
	?>
	<h1>Account overview</h1>
	<p>The account overview page shows all details for a specific account. This can be either your own account or that of someone else. Providing the owner of the account has not turned on the private option, you can view the following details of his/her account: <b>username</b>, which is the name of the account. It is unique to this user. The <b>fullname</b>, <b>email address</b> and <b>homepage</b> can also be viewed.</p>
	<p>If the user has ownership of one or more projects in Proms, they will show up in the <b>projects</b> listing.</p>
	<?
}

function BugAdd() {
	?>
	<h1>Report new bug</h1>
	<p>This screen is used to report a new bug. Reporting new bugs require an account. If you want to be a nice little user, please read the article about 'How To Report Bugs Effectively' before reporting a bug. This article describes the best practices for reporting bugs.</p>
	<p>From the <b>version</b> dropdown pick the version of the project to which the bug applies. In the <b>part</b> dropdown box you can choose an appropriate part of the project in which the bug occurred. You can choose the severity of the bug by selecting an option from the <b>severity</b> dropdown. The <b>subject</b> should very briefly give an indication of what the bug is about. Please do not use subjects like 'bug!' or 'Urgent!' or you will be shot down by the bug-reporting-god.</p>
	<p>The <b>description</b> field should be used to give a clear description of the bug. What errors are displayed, what behavior occurred and more importantly: what did you expect it to do instead? Use your common sense. The <b>reproduction</b> field can be used to enter a couple of steps which, if followed, will lead to the bug's occurrence. If you've got a potential patch for the problem, you can enter it into the <b>patch</b> field. The <b>software</b> field is for reporting any related software you were using when the bug occurred.</p>
	<p></p>
	<?
}

function BugList() {
	?>
	<h1>Bug List</h1>
	<p>The bugs list is one of the most important things in Proms. You can use it to get an a list of outstanding and closed bugs which have been reported about the current project. By default the list is sorted by the last modification date, so you can always see the bugs which are newest or which have been changed recently.</p>
	<p>The search block contains a number of input fields. These provide a way to filter the list of bugs which are shown so you will only see the bugs that are valuable to you. If you know the <b>bug number</b>, you can enter it into the appropriate field. This allows you to quickly view a bug which you may have been monitoring to see its current status. A more general way of searching for bugs is the <b>containing</b> field. You may enter any number of keywords here. Proms will then search the subject, descriptions, reproduction steps and the software fields to see if they match the keywords. Keywords are always searched for in an AND fashion. So if you specify 'navigation' and 'javascript' all bugs containing those two words in any of the above mentioned fields will be shown. By picking a status in the <b>status</b> dropdown, all bugs which don't have that particular status will not be shown. The same goes for the <b>Severity</b>, <b>versiom</b> and the <b>part</b> dropdowns. If you want to remove the filters, you can use the <a class="action">All bugs</a> button.</p>
	<p>The list itself shows the bugs which match the search criteria or, if no search criteria have been specified, all bugs. You can click the column headers to sort the column. Each row has a <a class="action">Modify</a> button. Clicking this button will take you to the Bug Modify screen.</p>
	<p>At the bottom of the page is a <a class="action">Report bug</a> button which can be used to report new bugs. Anyone with an account can report new bugs.</p>
	<?
}

function BugMod() {
	?>
	<h1>Modify bug</h1>
	<p>This screen is used to modify a bug's details. Bugs can only be modified by users who have the appropriate access to do so. All changes made to the information will be logged by the bug tracker to the forum. If you wish to see the bugs changes, please use the <a class="action">Discuss this bug</a> button in the <b>Bug overview</b> page.</p>
	<p>From the <b>version</b> dropdown pick the version of the project to which the bug applies. In the <b>part</b> dropdown box you can choose an appropriate part of the project in which the bug occurred. If the severity of the bug needs to be adjusted, you can choose the severity of the bug by selecting an option from the <b>severity</b> dropdown. The <b>status</b> dropdown shows you the current status of the bug. This can be changed to match any changes in the status of the bug. The <b>subject</b> should very briefly give an indication of what the bug is about. Please do not use subjects like 'bug!' or 'Urgent!' or you will be shot down by the bug-reporting-god.</p>
	<p>The <b>description</b> field should be used to give a clear description of the bug. What errors are displayed, what behavior occurred and more importantly: what did you expect it to do instead? Use your common sense. The <b>reproduction</b> field can be used to enter a couple of steps which, if followed, will lead to the bug's occurrence. If you've got a potential patch for the problem, you can enter it into the <b>patch</b> field. The <b>software</b> field is for reporting any related software you were using when the bug occurred.</p>
	<p>Upon saving a new bug, an e-mail report of the new bug will automatically be sent out to the project's maintainer or, if no maintainer is set, to the project's owner.</p>
	<?
}

function BugOverview() {
	?>
	<h1>Bug Information</h1>
	<p>This screen shows the information about a specific bug. The information shown is pretty straightforwards. At the bottom of the page you'll find three action buttons. <a class="action">Discuss this bug</a> takes you to the forums so you can discuss this bug. Each bug which will get its own thread in the forum. This forum is also used to track changes in the bugs information. Bug tracker posts in the forum will be shown in a different color than the normal posts. <a class="action">Report new bug</a> takes you to the page where a new bug can be reported. Every user with an account can report new bugs. The <a class="action">Modify bug</a> button will put you in the Bug modification screen.</p>
	<?
}

function ForumTopicList() {
	?>
	<h1>Forum Topics List</h1>
	<p>This list shows the general discussion topics for the forum of this project. The topics are shown when users enter the discussion forum. They can then pick one of the topics by clicking on its name and they will be put into the discussion list, where they can create new messages (threads) or participate in existing discussion threads. Users themselves cannot add new topics.</p>
	<p>Click on the <a class="action">Modify</a> button takes you to the page where you can edit the forum topic. The <a class="action">Delete</a> button deletes the current topic. The <a class="action">New topic</a> button at the bottom of the page allows you to add a new topic.</p>
	<?
}

function ForumTopicAdd() {
	?>
	<h1>Forum Topic Add</h1>
	<p>Adding a forum discussion topic is easy. Specify the <b>subject</b> of the topic and a <b>description</b> and you're done.</p>
	<?
}

function ForumTopicMod() {
	?>
	<h1>Forum Topic Modify</h1>
	<p>If you want to modify an existing forum topic you can do so here. You can change the <b>subject</b> and the <b>description</b> to anything you'd like.</p>
	<?
}

function ForumView() {
	?>
	<h1>Forum</h1>
	<p>The forum is used for discussing various things concerning the current project.</p>
	<h2>Topics</h2>
	<p>Each project has its own forum, which contains a set of topics. These topics can only be assigned by the project owner or anybody who has been authorized to modify the forums. When entering the discussion forums the first page you will see contains these topics, along with a description of each topic. You can view the posts (threads) in that topic by clicking the <b>View posts</b> link. A special topic called <b>bugs</b> exists (if any bugs have been discussed so far) in which you can discuss bugs. This special topic is integrated with the bug module and bug tracker, but you can also discuss bugs the normal way here. If you choose a topic, you will then be presented with the threads in this topic.</p>
	<h2>Threads</h2>
	<p>Each thread contains a single discussion. You can start a new thread in a topic by clicking the <a class="action">Start new thread</a> button at the bottom of the screen. You can also read an existing thread by clicking on its title. You will then see all the messages posted in this thread by every user. If you are currently in the <b>bugs</b> thread, you might also see some messages which have a different color then the other threads. (default: lightgreen) These messages are automatically posted by the bug tracker whenever somebody changes something in a bug report. You can join in on the discussion by clicking the <a class="action">Reply</a> button at the bottom of the screen. This will take you to the forum post page from which you can enter your message and post it to the specified thread in the forum.</p>
	<?
}

function ForumReply() {
	?>
	<h1>Forum Reply</h1>
	<p>Posting a reply in a thread is quite easy. Simply change the <b>subject</b> if need be, and type in your <b>message</b>. No html/bb-code/other stuff is allowed, just plain text.</p>
	<p>Upon posting a new reply, an e-mail notification of the post will automatically be sent out to the project's owner and all people subscribed to the thread.</p>
	<h2>BBCode</h2>
	<p>The following BBcode is supported</p>
	<p>
	<table>
		<tr valign="top"><td><b>Code</b></td><td>&nbsp;</td><td><b>Result</b></td></tr>
		<tr valign="top"><td>[b]bold[/b]                   </td><td>:</td><td><b>bold</b></td></tr>
		<tr valign="top"><td>[i]italic[/i]                 </td><td>:</td><td><i>Italic</i></td></tr>
		<tr valign="top"><td>[u]underline[/u]              </td><td>:</td><td><i>underline</i></td></tr>
		<tr valign="top"><td>[strike]strikethrough[/strike]</td><td>:</td><td><strike>strikethrough</strike></td></tr>
		<tr valign="top"><td>[li]listitem[/li]             </td><td>:</td><td><li>listitem</li></td></tr>
		<tr valign="top"><td>[quote]quote[/quote]          </td><td>:</td><td><span class="quote">quote</span></td></tr>
		<tr valign="top"><td>[code]code[/code]             </td><td>:</td><td><pre class="code">code</pre></td></tr>
		<tr valign="top"><td>[url=url]link[/url]           </td><td>:</td><td><a href="url">link</a></td></tr>
		<tr valign="top"><td>[url]url[/url]                </td><td>:</td><td><a href="url">url</a></td></tr>
	</table>
	</p>
	<?
}

function ReleaseList() {
	?>
	<h1>Release list</h1>
	<p>The release list shows all the releases for this project. Only one tree is supported, so you can't have branches. Some important information is shown which tells you the <b>release date</b>, <b>status</b> and the <b>focus</b> of the release. Clicking on the <b>version</b> of a release takes you to the release overview page.</p>
	<p>At the bottom of the page is a <a class="action">New release</a> button (if you are authorized to add releases). Clicking it brings up the new release page where you can enter the details for a new release.</p>
	<?
}

function ReleaseMod() {
	?>
	<h1>Release Modify</h1>
	<p>This page allows you to make changes in a release's information. The <b>version</b> should always be filled in. If the release has a codename, you can enter it in the <b>codename</b> field. The <b>date</b> can be changed so you can add older releases or set the release date to the actual date the version was released at. If you set the date in the future, the release will stay hidden until that date. The <b>release focus</b> allows you to specify the focus of this release. This way your users will know the major reason for this release. To give an indication of the current status of the program and/or code of the program, you can change the <b>release status</b> dropdown. The <b>changes</b> field allows you to summarize the major changes in this release.</p>
	<p>Following are a number of fields which you can use to create links to the actual downloads of the version and to additional documentation concerning the release. For now you cannot use Proms to upload and store releases, so you will have to store them somewhere else for the moment. Use these fields to link to their location.</p>
	<?
}

function ReleaseOverview() {
	?>
	<h1>Release Overview</h1>
	<p>This page shows detailed information for a particular release. All the information about the release which is available is shown.</p>
	<p><b>Version</b> is the version of the release. If this release also has a <b>codename</b>, it will be shown behind the version. <b>Date</b> is, by default, the date on which the release was added to Proms. This release date can be changed by the administrator or release manager however to reflect, i.e. the actual date of release. The <b>release focus</b> in essence shows you why this release was made. For instance, if the release focus is 'major bugfixes' than some major bugs where fixed in this release. <b>Release status</b> shows you the current status of the code and program. This can be anything from 'Stable' to 'Beta'.</p>
	<p>The major <b>changes</b> from the previous version to this version are also shown. For the full list of changes, including the minor ones, you should check the <b>changelog</b> link.</p>
	<p>Following the <b>changes</b> are a bunch of URL's to the actual released version. A couple of different packages can be entered by the release manager. If a particular type of package (i.e. RPM) isn't available, you'll see 'N/A'.</p>
	<p>Finally there are two links to additional readings. <b>Changelog</b> links to the document containing the full list of changes. <b>Releasenotes</b> points to a document which contains information about this release. Mostly things that are important to know if you are upgrading or something like that.</p>
	<p>At the bottom of the page you will find the <a class="action">Modify</a> button which allows the release manager to modify the release.</p>
	<?
}

function ReleaseAdd() {
	?>
	<h1>Release Add</h1>
	<p></p>
	<p>This page allows you to add a new release's information. The <b>version</b> should always be filled in. If the release has a codename, you can enter it in the <b>codename</b> field. The <b>date</b> can be changed so you can add older releases or set the release date to the actual date the version was released at. If you set the date in the future, the release will stay hidden until that date. The <b>release focus</b> allows you to specify the focus of this release. This way your users will know the major reason for this release. To give an indication of the current status of the program and/or code of the program, you can change the <b>release status</b> dropdown. The <b>changes</b> field allows you to summarize the major changes in this release.</p>
	<p>Following are a number of fields which you can use to create links to the actual downloads of the version and to additional documentation concerning the release. For now you cannot use Proms to upload and store releases, so you will have to store them somewhere else for the moment. Use these fields to link to their location.</p>
	<p>After the new release has been saved, people subscribed to the project will automatically receive an e-mail notification of the new release.</p>
	<?
}

function TodoAdd() {
	?>
	<h1>Todo Add</h1>
	<p>To add a todo to the list you must choose a <b>priority</b> from the dropdown list. This lets other developers know how important the todo is. The <b>part</b> can be used to specify in which part of the project this todo belongs. This way each part of the project can be maintained by another (couple of) user(s), and they can easily see what things still need to be done for their part. The <b>subject</b> and <b>description</b> speak for themselves.</p>
	<p>If you mark the checkbox <b>Add another todo</b> at the bottom of the page and save the current todo, you will be immediately presented with another empty todo screen. This way you can add a number of todo's more easily.</p>
	<p>Upon saving the new todo, an e-mail notification of the new todo will automatically be sent to the project's maintainer or, if no maintainer has been set, the project's owner.</p>
	<?
}

function TodoMod() {
	?>
	<h1>Modify todo</h1>
	<p>To modify a todo you must choose a <b>priority</b> from the dropdown list. This lets other developers know how important the todo is. The <b>part</b> can be used to specify in which part of the project this todo belongs. This way each part of the project can be maintained by another (couple of) user(s), and they can easily see what things still need to be done for their part. The <b>subject</b> and <b>description</b> speak for themselves.</p>
	<?
}

function TodoList() {
	?>
	<h1>Todo list</h1>
	<p>The todo list shows an overview of all the todo's for this project. Each todo is assigned a number automatically. You can search for todo's and filter out todo's which do not meet a certain criteria.</p>
	<p>The <b>search</b> box gives you a range of options. These options can be used to search for todo's and for filtering out unwanted todo's. If you know the <b>todo # (number)</b>, you can enter it into the appropriate field, and only the todo with that number will be shown. By entering some keywords into the <b>containing</b> field you can perform an AND search on the todo's. In that case Proms will only show you the todo's whose subject or description contains all the keywords you entered. By choosing a value from the dropdown lists you can filter out only those todo's that match the <b>status</b>, <b>priority</b>, <b>part</b> or <b>user</b> you chose.</p>
	<p>The list itself shows the todo's matching the search criteria. You click the column headers to sort the list according to the column header. If you click the todo #, you will be taken to the todo overview page, which will show you all available information about that todo. The <a class="action">Modify</a> button is a quick way to get to the screen where you can edit the todo's information. The <b>checkboxes</b> next to the Modify button can be used to quickly mark bugs as done. Simply check the boxes of the todo's you have completed and click the <input type="button" value="Done"> button. Todo's which already have been marked as Done can't be checked because their checkboxes have been disabled.</p>
	<p>At the bottom the <a class="action">Add todo</a> button can be found. This button will bring up the screen which allows you to add a new todo.</p>
	<?
}

function TodoOverview() {
	?>
	<h1>Todo Overview</h1>
	<p>If, in the todo list, you click the todo's number, you will be taken to this page. This is where you get to see all of a todo's details. If the administrator of the project didn't set the project's progress manually, then Proms will use the pending and done todo's to determine the current project's progress.</p>
	<p>At the top of the page is some general information, namely the <b>todo #</b>, its title and the <b>user by</b> which it was first reported.</p>
	<p>Next is the current status of the todo. The <b>status</b> shows you if the todo is either pending or done. The <b>priority</b> is to determine how important the todo is. <b>Part</b> tells you to which part of the project this todo applies.</p>
	<p>The <b>last modification date</b> is also shown. This way you can see when the todo's information last changed. After that is the <b>description</b>.</p>
	<p>At the bottom are the usual actions. <a class="action">Discuss this todo</a> will take you to the forum where you can discuss the todo with other users. If you want to add another todo to the list, you can use the <a class="action">Add todo</a> action button. The current todo can be modified by using the <a class="action">Modify todo</a> button.</p>
	<?
}

function FileList() {
	?>
	<h1>File list</h1>
	<p>The file list shows the files that have been uploaded. They are broken down in <b>categories</b> and for each category a <b>description</b> is shown. For each file the <b>filename</b>, a <b>version</b>, its <b>size</b> and a <b>description</b> are shown. Clicking on the filename allows you to download the file.</p>
	<p>If you've got the proper access, you can modify a category by clicking on the edit button behind the title. File information can be modified by clicking the edit button behind the file's information. New categories can be added by clicking the <a class="action">Add cateogry</a>. New files can be uploaded using the <a class="action">Add file</a> action.</p>
	<?
}
function FileAdd() {
	?>
	<h1>Add file</h1>
	<p>Uploading a file is easy. Choose a <b>category</b> in which the new file should appear from the list. Next, chose the file to <b>upload</b> by clicking the 'Browse' button. For the <b>filename</b> it is advised to enter the same name as for other versions of the same file. Next, enter the <b>versiom</b> of the uploaded file and a <b>description</b> that users can read to distinguish between different (versions of) files.</p>
	<?
}
function FileMod() {
	?>
	<h1>Modify file information</h1>
	<p>When modifying a file, you can change the <b>category</b>, which will move the file to a different category. You may also change the file's <b>title</b>, <b>version</b> and <b>description</b>.</p>
	<p>It is (at the moment) not possible to overwrite the contents of the old file. If you've made a mistake, you will either have to upload a new version of the file or get access to the files themselves. (Contact the Proms administrator).</p>
	<?
}
function FileCategoryList() {
	?>
	<h1></h1>
	<p></p>
	<p>The <b>last modification date</b> is also shown. <a class="action">Discuss this todo</a> will take </p>
	<?
}
function FileCategoryAdd() {
	?>
	<h1>Add file category</h1>
	<p>Here you can add a new file category. File categories are unique per project and consist out of a <b>name</b> and a <b>description</b>. Users with correct permissions can then upload files to the category.</p>
	<?
}
function FileCategoryMod() {
	?>
	<h1>Modify file cateogry</h1>
	<p>Here you can modify a file category. File categories are unique per project and consist out of a <b>name</b> and a <b>description</b>. Users with correct permissions can then upload files to the category.</p>
	<?
}

?>


