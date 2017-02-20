# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "sass"
images_dir = "images"
javascripts_dir = "js"

# You can select your preferred output style here (can be overridden via the command line):
# :expanded or :compact or :compressed
output_style = :nested 

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

# Uncomment below For WordPress. SRC: http://css-tricks.com/compass-compiling-and-wordpress-themes/
# require 'fileutils'
# on_stylesheet_saved do |file|
#  if File.exists?(file) && File.basename(file) == "master.css"
#    puts "Moving #{file}"
#    FileUtils.mv(file, File.dirname(file) + "/../style.css")
# + File.basename(file)
#  end
#end
