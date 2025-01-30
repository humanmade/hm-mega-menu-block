# How to Use the HM Mega Menu Block Plugin

https://www.loom.com/embed/b91514e4eb2b4d939f128ac194b13c7d?sid=58fd2f1e-5b2b-4e40-ba1a-da8210c8c54c

This guide explains how to create and customize a mega menu using the HM Mega Menu Block plugin.

---

## Installation and usage

### Step 1: Install and Activate the HM Mega Menu Block Plugin
1. **Install the Plugin**:
   - If the plugin is not installed yet, upload or install it from the plugin directory.
2. **Activate the Plugin**:
   - Go to **Plugins > Installed Plugins** and activate the `HM Mega Menu Block` plugin.

---

### Step 2: Add a New Mega Menu Template Part
1. **Open the Site Editor**:
   - Go to **Appearance > Editor** to access the block editor.
2. **Create a New Template Part**:
   - In the editor, click **Add New** to create a new pattern or template part.
   - Assign a name to the template part (e.g., "Mega Menu").
   - Designate it as a **Mega Menu** type.

---

### Step 3: Build the Mega Menu Layout
1. **Add Groups**:
   - In the newly created template part, use **Group** blocks to structure the menu.
   - Add nested groups for creating multi-level menus.
2. **Add Content**:
   - Insert blocks like:
     - **Navigation Block** for menu links.
     - **Heading Block** for section titles.
     - Other blocks (e.g., images, buttons) to customize the layout.
3. **Customize**:
   - Add any colors, styles, or additional elements to personalize the mega menu.

---

### Step 4: Link the Mega Menu to the Header
1. **Edit the Header Template Part**:
   - In the Site Editor, open the **Header** template part.
2. **Insert the HM Mega Menu Block**:
   - Add the `HM Mega Menu Block` to the header inside the **Navigation Block**.
   - Add a label to the Mega Menu uat the block section, on the right side of your screen.
   - Link it to the template part created for the mega menu (e.g., "Mega Menu").
3. **Save Changes**

---

### Step 5: Test the Mega Menu
1. **Preview on the Front-End**:
   - Visit your site's homepage to see the mega menu in action.

---

### Step 6: Make Final Adjustments
1. **Fix Positioning Issues**:
   - Ensure the mega menu is properly aligned and sticky (if needed).
2. **Enhance Styling**:
   - Customize colors, spacing, and other design elements to match your site.

---

## Notes and Tips
- You can create multiple mega menus by repeating these steps and linking them to different template parts.
- Adjust sticky positioning or other advanced behaviors as necessary.

This plugin provides a flexible, code-light way to build and customize mega menus in WordPress!

## Release Process

Merges to `main` will automatically build to the `release` branch.

Commits in the `release` branch may be [tagged for installation via packagist](https://packagist.org/packages/humanmade/hm-mega-menu-block) and [composer](http://getcomposer.org/), and optionally marked as releases in GitHub for download. A project may also be set up to track the `dev-release` branch to always pull in the latest built beta version.

## Changelog

### 1.0.0

Initial Release.
