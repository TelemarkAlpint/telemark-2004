#!/store/bin/perl

##############################################################################
# Guestbook                     Version 2.3.1                                #
# Copyright 1996 Matt Wright    mattw@worldwidemart.com                      #
# Created 4/21/95               Last Modified 10/29/95                       #
# Scripts Archive at:           http://www.worldwidemart.com/scripts/        #
##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 1996 Matthew M. Wright  All Rights Reserved.                     #
#                                                                            #
# Guestbook may be used and modified free of charge by anyone so long as     #
# this copyright notice and the comments above remain intact.  By using this #
# code you agree to indemnify Matthew M. Wright from any liability that      #  
# might arise from it's use.                                                 #  
#                                                                            #
# Selling the code for this program without prior written consent is         #
v# expressly forbidden.  In other words, please ask first before you try
and  #
# make money off of my program.                                              #
#                                                                            #
# Obtain permission before redistributing this software over the Internet or #
# in any other medium.	In all cases copyright and header must remain intact.#
##############################################################################
# Set Variables

$guestbookurl = "http://www.stud.ntnu.no/studorg/telemark/paa_verdensveven/sladdrespalte.html";
$guestbookreal="/local/www.stud/stud/htdocs/studorg/telemark/paa_verdensveven/sladdrespalte.html";
$cgiurl = "http://www.stud.ntnu.no/studorg/telemark/paa_verdensveven/cgi/sladdre.pl";

# Set Your Options:
$mail = 1;              # 1 = Yes; 0 = No
$uselog = 1;            # 1 = Yes; 0 = No
$linkmail = 1;          # 1 = Yes; 0 = No
$separator = 1;         # 1 = <hr>; 0 = <p>
$redirection = 0;       # 1 = Yes; 0 = No
$entry_order = 1;       # 1 = Newest entries added first;
                        # 0 = Newest Entries added last.
$remote_mail = 0;       # 1 = Yes; 0 = No
$allow_html = 1;        # 1 = Yes; 0 = No
$line_breaks = 0;	# 1 = Yes; 0 = No

# If you answered 1 to $mail or $remote_mail you will need to fill out 
# these variables below:
$mailprog = '/usr/lib/sendmail';
$recipient = 'matsbjor@stud.ntnu.no';

require 'formdate.pl';
# Done
##############################################################################

# Get the Date for Entry
%date = &format_date (time);

# Get the input
read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});

# Split the name-value pairs
@pairs = split(/&/, $buffer);

foreach $pair (@pairs) {
   ($name, $value) = split(/=/, $pair);

   # Un-Webify plus signs and %-encoding
   $value =~ tr/+/ /;
   $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
   $value =~ s/<!--(.|\n)*-->//g;

   if ($allow_html != 1) {
      $value =~ s/<([^>]|\n)*>//g;
   }

   $FORM{$name} = $value;
}

# Print the Blank Response Subroutines
&no_comments unless $FORM{'comments'};
&no_name unless $FORM{'realname'};

# Begin the Editing of the Guestbook File
open (FILE,"$guestbookreal") || die "Can't Open $guestbookreal: $!\n";
@LINES=<FILE>;
close(FILE);
$SIZE=@LINES;

# Open Link File to Output
open (GUEST,">$guestbookreal") || die "Can't Open $guestbookreal: $!\n";

for ($i=0;$i<=$SIZE;$i++) {
   $_=$LINES[$i];
   if (/<!--begin-->/) { 

      if ($entry_order eq '1') {
         print GUEST "<!--begin-->\n";
      }
       if ($FORM{'realname'}) {
         print GUEST "<b>$FORM{'realname'}</b><br><br>";
      }                                   
                                
      if ($line_breaks == 1) {
         $FORM{'comments'} =~ s/\cM\n/<br>\n/g;
      }

      print GUEST "<blockquote>$FORM{'comments'}</blockquote>\n";


      if ( $FORM{'username'} ){
         if ($linkmail eq '1') {
            print GUEST " \&lt;<a href=\"mailto:$FORM{'username'}\">";
            print GUEST "$FORM{'username'}</a>\&gt;<br>";
         }
         else {
            print GUEST " &lt;$FORM{'username'}&gt;<br>";
         }
      }


      if ($separator eq '1') {
         print GUEST "Submitted: $date{'df'}-$date{'month'}-$date{'year'}<hr>\n";

	}
      else {
         print GUEST " - $date{'df'} - $date{'month'} - $date{'year'}<p>\n\n";
      }
                                  

      if ($entry_order eq '0') {
         print GUEST "<!--begin-->\n";
      }

   }
   else {
      print GUEST $_;
   }
}

close (GUEST);

# Log The Entry

if ($uselog eq '1') {
   &log('entry');
}


#########
# Options

# Mail Option
if ($mail eq '1') {
   open (MAIL, "|$mailprog $recipient") || die "Can't open $mailprog!\n";

   print MAIL "Reply-to: $FORM{'username'} ($FORM{'realname'})\n";
   print MAIL "From: $FORM{'username'} ($FORM{'realname'})\n";
   print MAIL "Subject: Ny melding\n\n";
   print MAIL "Det har kommet en ny melding:\n\n";
   print MAIL "------------------------------------------------------\n";
   print MAIL "$FORM{'comments'}\n";
   print MAIL "$FORM{'realname'}";

   if ( $FORM{'username'} ){
      print MAIL " <$FORM{'username'}>";
   }

   print MAIL "\n";

   print MAIL " - $date\n";
   print MAIL "------------------------------------------------------\n";

   close (MAIL);
}

if ($remote_mail eq '1' && $FORM{'username'}) {
   open (MAIL, "|$mailprog -t") || die "Can't open $mailprog!\n";

   print MAIL "To: $FORM{'username'}\n";
   print MAIL "From: $recipient\n";
   print MAIL "Subject: Recipe\n\n";
   print MAIL "Thank you for adding a recipe.\n\n";
   print MAIL "------------------------------------------------------\n";
   print MAIL "$FORM{'comments'}\n";
   print MAIL "$FORM{'realname'}";

   if ( $FORM{'username'} ){
      print MAIL " <$FORM{'username'}>";
   }

   print MAIL "\n";

   if ( $FORM{'city'} ){
      print MAIL "$FORM{'city'},";
   }

   if ( $FORM{'state'} ){
      print MAIL " $FORM{'state'}";
   }

   if ( $FORM{'country'} ){
     print MAIL " $FORM{'country'}";
   }

   print MAIL " - $date\n";
   print MAIL "------------------------------------------------------\n";

   close (MAIL);
}

# Print Out Initial Output Location Heading
if ($redirection eq '1') {
   print "Location: $guestbookurl\n\n";
}
else { 
   &no_redirection;
}

#######################
# Subroutines

sub no_comments {
   print "Content-type: text/html\n\n";
   print "<html><head><title>No Comments</title></head>\n";
   print "<body><h1>Du har ikke skrevet inn noen melding</h1>\n";
   print "Du må skrive inn en melding i meldingsfeltet under\n";
   print "<form method=POST action=\"$cgiurl\">\n";
   print "Your Name:<input type=text name=\"realname\" size=30 ";
   print "value=\"$FORM{'realname'}\"><br>\n";
   print "E-Mail: <input type=text name=\"username\""; 
   print "value=\"$FORM{'username'}\" size=40><br>\n";
   print "Melding:<br>\n";
   print "<textarea name=\"comments\" COLS=60 ROWS=4></textarea><p>\n";
   print "<input type=submit> * <input type=reset></form><hr>\n";
   print "Tilbake til <a href=\"$guestbookurl\">meldingssiden</a>.";
   print "\n</body></html>\n";

   # Log The Error
   if ($uselog eq '1') {
      &log('no_comments');
   }

   exit;
}

sub no_name {
   print "Content-type: text/html\n\n";
   print "<html><head><title>No Name</title></head>\n";
   print "<body><h1>Du har ikke skrevet inn navn!!</h1>\n";
   print "Du må skrive inn navn! Gjør det i feltet under\n";
   print "<form method=POST action=\"$cgiurl\">\n";
   print "Navn:<input type=text name=\"realname\" size=30><br>\n";
   print "E-Mail: <input type=text name=\"username\"";
   print "The recipie value=\"$FORM{'username'}\" size=40><br>\n";
   print " har blitt lagret.<p>\n";
   print "<input type=hidden name=\"comments\" ";
   print "value=\"$FORM{'comments'}\">\n";
   print "<input type=submit> * <input type=reset><hr>\n";
   print "Tilbake til <a href=\"$guestbookurl\">meldingssiden</a>.";
   print "\n</body></html>\n";

   # Log The Error
   if ($uselog eq '1') {
      &log('no_name');
   }

   exit;
}

# Log the Entry or Error
sub log {
   $log_type = $_[0];
   open (LOG, ">>$guestlog");
   if ($log_type eq 'entry') {
      print LOG "$ENV{'REMOTE_HOST'} - [$shortdate]<br>\n";
   }
   elsif ($log_type eq 'no_name') {
      print LOG "$ENV{'REMOTE_HOST'} - [$shortdate] - ERR: No Name<br>\n";
   }
   elsif ($log_type eq 'no_comments') {
      print LOG "$ENV{'REMOTE_HOST'} - [$shortdate] - ERR: No ";
      print LOG "Comments<br>\n";
   }
}




# Redirection Option
sub no_redirection {

   # Print Beginning of HTML
   print "Content-Type: text/html\n\n";
   print "<html><head><title>Takk</title></head>\n";
   print "<body><h1>Takk for at du skrev en melding!!</h1>\n";

   # Print Response
   print "Din melding er lagret<hr>\n";
   print "Her er det du skrev:<p>\n";
   print "<b>$FORM{'comments'}</b><br>\n";

      print "$FORM{'realname'}";
   }

   if ( $FORM{'username'} ){
      if ($linkmail eq '1') {
         print " &lt;<a href=\"mailto:$FORM{'username'}\">";
         print "$FORM{'username'}</a>&gt;";
      }
      else {
         print " &lt;$FORM{'username'}&gt;";
      }
   }

   print "<br>\n";


   print " - $date<p>\n";

   # Print End of HTML
   print "<hr>\n";
   print "<a href=\"$guestbookurl\">Tilbake til meldingssiden</a>\n";
print "- Trykk reload hvis din melding ikke kommer til syne\n";
   print "</body></html>\n";

   exit;


