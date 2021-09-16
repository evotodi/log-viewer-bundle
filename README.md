# Simple log Viewer Symfony Bundle

LogViewerBundle is a basic log viewer for symfony. 
It allows you to list and view all of the symfony logs or external logs in one easy 
place with level highlighting and level filtering.

###<span style="color:orange">Caution:</span>
Upgrading from a version older than 1.4 will break your log patterns. See [breaking changes](#breaking-changes) below.

## Installation
Install the package with:
```console
composer require evotodi/log-viewer-bundle
```
##  Configuration

Create the routes yaml file `config/routes/evo_log_viewer_routes.yaml`

```yaml
evo_log_viewer:
  resource: '@EvotodiLogViewerBundle/Resources/config/routes.xml'
  prefix: '/logs'
```
Create the config yaml file `config/packages/evo_log_viewer.yaml`
```yaml
# List of log files to show
evo_log_viewer:
    log_files:
        # Unique identifier for the logfile
        somelog1:
            # Use full path
            path: 'Some/Full/Path/to/Log/File.Ext'

            # Pretty name to display else file name
            name: My Log Files Pretty Name 

            # (Optional) Number of days to pull from log. See ddtraceweb/monolog-parser.
            days: 0

            # (Optional) See ddtraceweb/monolog-parser for patterns.
            pattern: null

            # (Optional) PHP style date format of log file
            date_format: 'Y-m-d H:i:s'

            # (Optional) Use P<channel> in the pattern
            use_channel: true

          # (Optional) Use P<level> in the pattern
            use_level: true
            
            # (Optional) Log level spelling. Case sensitive
            levels:
                debug: DEBUG
                info: INFO
                notice: NOTICE
                warning: WARNING
                error: ERROR
                alert: ALERT
                critical: CRITICAL
                emergency: EMERGENCY

        somelog2:
            path: '/path/to/logfile.log'
            name: Pretty Logfile Name

    # Show App logs in var/log
    show_app_logs: true
    
    # (Optional) Change the default parser pattern
    app_pattern: '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/'
    
    # (Optional) Change the default date format
    app_date_format: 'Y-m-d H:i:s'
```
## Advanced Configuration

#### pattern
The default pattern is `'/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/'`
\
You can change the regex pattern to match your log file but the pattern must include `P<date>`, `P<channel>`, `P<level>`, and `P<message>` as regex groups. You can omit the `P<channel>` and `P<level>` by setting use_channel: false and use_level: false respectively.
\
Example `'/\[(?P<date>.+)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*)/'`
\
See ddtraceweb/monolog-parser for other examples but ommit `P<context>` and `P<extra>`

#### days
Setting days in the config to 0 will parse to whole log which is the default. Days set to 5 for example will parse the log until the date portion of the pattern
if greater than DateTime('now') minus 5 days.

#### date_format
This should be the php date format of the date portion of the pattern. Default is Y-m-d H:i:s
/
[PHP DateFormat](https://www.php.net/manual/en/function.date.php)

#### levels
Override the default spelling for each level. e.g. WARNING -> WARN

#### Breaking Changes
Updating from an older version to version >=1.4 will break your log patterns. This is easy to fix, just change `P<logger>` to `P<channel>`.

## Thanks
Thanks to ddtraceweb/monolog-parser and greenskies/web-log-viewer-bundle.

## Contributions
Contributions are very welcome! 

Please create detailed issues and PRs.  

## License

This package is free software distributed under the terms of the [MIT license](LICENSE).
