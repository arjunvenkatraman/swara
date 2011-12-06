#!/usr/bin/perl

  ####################################################
  # uploadrelais 0.1                                 #
  # by Christian Rotzoll                             #
  #                                                  #
  # christian.rotzoll@medien.uni-weimar.de           #
  # Doku unter http://www.rotzoll.de/cgiuploadrelais #
  #                                                  #
  ####################################################
  # Upload-Script                                    #
  ####################################################

use CGI::Carp ('fatalsToBrowser');
use CGI ('param', 'upload');

######################################################
## CONFIG
######################################################

# Keep the file for X minutes
$MAX_PROXY_TIME = 20;

# Maximal traffic per month (in MB)
$TRAFFIC_LIMIT = 3000;

# Allowed file extensions
@ALLOWED_EXTENSIONS = ("mp3", "ogg", "aif", "wav", "m4a", "acc", "aiff", "wma", "wmf", "rm", "ram", "mp4");

# forbidden file extensions
@FORBIDDEN_EXTENSIONS = ("exe", "com", "php", "cgi");

#######################################################
# OK ... ab hier nur wer das Script modden will
#######################################################

$ausgabe = "";

####################
## INIT
####################

# < -- ggf. Upload-Verzeichnis anlegen
mkdir("uploadrelaisdaten",0777);
# -- >

# < -- ggf. Upload-Verzeichnis gegen Zugriff von außen schuetzen
if (-r "uploadrelaisdaten/.htaccess") {
  #print "<! Found .htaccess>\n";
}
else
{
  open(HTACCESS, ">uploadrelaisdaten/.htaccess")
    or die("Kann nicht schreiben! (Screibrechte fuer das Verzeichnis setzen).");
  print HTACCESS "# Zugriffsschutz \nOrder deny,allow\nDeny from all";
  close(HTACCESS);
  #print "<! .htaccess created>\n";
}
# -- >

# < -- ggf. CounterDatei erstellen (laufende Nummer fuer UploadIDs)
if (-r "uploadrelaisdaten/uploadrelais.data.txt") {
  #print "<! Found uploadrelais.data.txt>\n";
}
else
{
  open(IDCOUNTER, ">uploadrelaisdaten/uploadrelais.data.txt")
    or die("Kann nicht schreiben! (Schreibrechte fuer das Verzeichnis setzen).");
  print IDCOUNTER "0\n";   		# idcounter
  print IDCOUNTER time()."\n"; 	# Zeitpunkt der letzten Trafficlöschung
  print IDCOUNTER "0\n"; 		# trafficcounter
  close(IDCOUNTER);
  #print "<! uploadrelais.data.txt created>\n";
}
# -- >


####################
# CLEAN UP
####################

@delIDs = ();

opendir(DIR,"uploadrelaisdaten");
while($datei = readdir(DIR)) {
  if (dateiEndung($datei) eq "inf")
  {
    @teile = split(/\./,$datei);
    $id = shift(@teile);
    push(@delIDs,$id);
  }
}
while ($id = pop(@delIDs))
{
  open(INF,"<uploadrelaisdaten/$id.inf");
  $upload_time = <INF>;
  close INF;

  $diff_time = time() - $upload_time;
  if ($diff_time >= ($MAX_PROXY_TIME * 60))
  {
    #print "<! clean up: $id>\n";
    unlink("uploadrelaisdaten/$id.inf");
    unlink("uploadrelaisdaten/$id.dat");
    unlink("uploadrelaisdaten/$id.sta");
  }
}
closedir(DIR);

####################
## PRAMETER CHECK
####################


$checkresult = "GO";

# - callback_script-Parameter pruefen
$callback_script = param('callback_script');
if ($callback_script eq "") {
  $ausgabe = $ausgabe . "ERROR: parameter 'callback_script' was not set.";
  $checkresult = "ABORT";
}

# später hier:
# - TrafficLimit pruefen

if ($checkresult eq "GO")
{
  # -- upload_id laden/generieren ---------


  open(IDCOUNTER, "<uploadrelaisdaten/uploadrelais.data.txt")
   or die("kann nicht lesen: $!");
  $upload_id       = <IDCOUNTER>;
  $traffic_time    = <IDCOUNTER>;
  $traffic_counter = <IDCOUNTER>;
  close(IDCOUNTER);

  $upload_id    	= int(trimwhitespace($upload_id));
  $traffic_time    	= int(trimwhitespace($traffic_time));
  $traffic_counter 	= int(trimwhitespace($traffic_counter));
  $traffic_limit	= $TRAFFIC_LIMIT;
  $upload_id += 1;

  #print "<! upload_id = $upload_id>\n";
  #print "<! traffic_time = $traffic_time>\n";
  #print "<! traffic_counter = $traffic_counter Byte>\n";

  # pruefen, ob ein Monat um ist ...
  $diff_time = time() - $traffic_time;
  $einMonatInSekunden = 60 * 60 * 24 * 31;
  # da immer 31 Tage = Monat verschiebt sich der Stichtag immer ein wenig
  # aber der Einfachheit halber ...
  if ($diff_time >= $einMonatInSekunden)
  {
    # Trafficcounterreset
    $traffic_counter = 0;
    $traffic_time 	 = time();
  }

  # pruefen, ob Traffic-Grenze erreicht wurde
  if ($traffic_limit > 0)
  {
    #print "<! Trafficlimit bei $traffic_limit MB>\n";
    #$traffic_counter_mb = int($traffic_counter / (1024 * 1024));
    #print "<! Trafficverbrauch bisher bei $traffic_counter_mb MB>\n";

    $traffic_limit_byte = int($traffic_limit * 1024 * 1024);
    if ( $traffic_limit_byte <= int($traffic_counter) )
    {
      $tage = int(($einMonatInSekunden - $diff_time) / (60 * 60 * 24));

      # Falls nur Status angefordert wurde, muss jetzt
      # zu hoher Traffic signalisiert werden
      if ($callback_script eq "status")
      {
        print "Content-type: text/plain\n\n";
        print "OVER TRAFFICLIMIT\n".$tage;
        exit();
      }
      fehlerMedlung("Traffic limit exceeded. You can uoload again in ".$tage." days again.");

    }
  }
  else
  {
    #print "<! Kein Trafficlimit>\n";
  }

  # Falls nur Status angefordert wurde, kann jetzt
  # gruenes Licht gegeben werden
  if ($callback_script eq "status")
  {
    print "Content-type: text/plain\n\n";
    print "ALL SYSTEMS\nGO!";
    exit();
  }


  # -- Upload entgegennehmen ---------

  $original_name 	= param('file');

  if (! $original_name) {
    die("Upload war nicht erfolgreich");
  }

  # ggf. Dateiname von Vezeichnissen isolieren
  @teile = split(/\\/,$original_name);
  $original_name = pop(@teile);


  # Filename auf Sonderzeichen ueberpruefen
  #if($original_name !~ /^[a-z\.\-_]+?\.([a-z]{3})$/)
  #{ die("FEHLER: Ungueltiger Dateiname"); }

  # Filename auf gueltige Endung ueberpruefen
  $endung = lc(dateiEndung($original_name));
  #print "<! Dateiendung: $endung>";
  if ("@ALLOWED_EXTENSIONS" ne "")
  {
    if(grep(/$endung/i, @ALLOWED_EXTENSIONS)== 0)
    { fehlerMedlung("Dateien mit der Endung '$endung' sind nicht erlaubt.<br>(Erlabute Endungen: @ALLOWED_EXTENSIONS)"); }
  }
  if(grep(/$endung/i, @FORBIDDEN_EXTENSIONS) >= 1)
  { fehlerMedlung("Dateien mit der Endung '$endung' sind verboten.<br>(Weitere verbotene Endungen: @FORBIDDEN_EXTENSIONS)"); }

  $filehandle = upload('file');


  binmode($filehandle);

  #Datei-Info speichern
  open(OUT, ">uploadrelaisdaten/$upload_id.inf")
    or die("cannot write: $!");
  print OUT time()."\n";
  print OUT "$original_name\n";
  close OUT;

  # Datei-Daten speichern
  open(OUT, ">uploadrelaisdaten/$upload_id.dat")
    or die("cannot write: $!");
  binmode(OUT);
  $bytecounter = 0;
  while ($zeile = <$filehandle>) {
    print OUT $zeile;
    $bytecounter += length($zeile);
  }
  close OUT;
  close $filehandle;

  if ($bytecounter == 0)
  {
    fehlerMedlung("0 Byte received. Please return.");
  }
  else
  {
    # Traffic fuer Up- & Download berechnen
    $traffic_counter = $traffic_counter + (2 * $bytecounter);
  }

 open(IDCOUNTER, ">uploadrelaisdaten/uploadrelais.data.txt")
    or die("cannot write! (please set permissions).");
  print IDCOUNTER $upload_id."\n";
  print IDCOUNTER $traffic_time."\n";
  print IDCOUNTER $traffic_counter;
  close(IDCOUNTER);

  # -- Weiterleitung zum Zielscript -----------

  print "Content-type: text/html\n\n";
  print "<html><head>\n";
  print "<meta http-equiv='refresh' content='1; url=$callback_script&amp;uploadid=$upload_id'>\n";
  print "</head>";
}
else
{
  print "<! Pramatercheckliste negativ>\n";
}

# ---- Textausgabe
print "<body>\n";
if ($ausgabe eq "")
{
  print "<br><b>... transferring data ...</b>";
}
else
{
  print $ausgabe;
}


# ---- HTML-ENDE
print "\n</body>\n";
print "</html>";

sub dateiEndung
{
 $dateiname = shift;
 @teile = split(/\./,$dateiname);
 $endung = pop(@teile);
 return($endung);
}

sub fehlerMedlung
{
 $nachricht = shift;
  print "Content-type: text/html\n\n";
  print "<html><head>\n";
  print "<body>\n<br><br><center>";
  print "<b>An error occured while uploading:</b> ";
  print $nachricht;
  print "<br><br>Use the back-button of your browser.";
  print "\n</center></body>\n";
  print "</html>";
 exit;
}

sub trimwhitespace
{
	$string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}
