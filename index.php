<?php 


$pdfText = $statusMsg = ''; 
$status = 'error';

// if the form is submitted
if(isset($_POST['submit'])){ 
    // If file is selected 
    if(!empty($_FILES["pdf_file"]["name"])){ 
        // File upload path 
        $fileName = basename($_FILES["pdf_file"]["name"]); 
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('pdf'); 
        if(in_array($fileType, $allowTypes)){ 
            // Include autoloader file 
            include 'vendor/autoload.php'; 
             
            // Initialize and load PDF Parser library 
            $parser = new \Smalot\PdfParser\Parser(); 
             
            // Source PDF file to extract text 
            $file = $_FILES["pdf_file"]["tmp_name"]; 
            // $file = substr($file_i, 0, strripos($file_i, '.'));
             
            // Parse pdf file using Parser library 
            $pdf = $parser->parseFile($file); 
             
            // Extract text from PDF 
            $text = $pdf->getText(); 
             
            // Add line break 
            $pdfText = nl2br($text); 

            //*********************TRAITEMETN DE FICHIER TEXTE*********************************** */
            // création du fichier texte pour stocker le contenu du pdf
            //stocker le nom du pdf dans une variable
            $nom_du_pdf = $_FILES["pdf_file"]["name"];
            // fichier text dans lequel sera stocké le contenu du pdf
            $nom_file = $nom_du_pdf.".txt";
            $f = fopen($nom_file, "x+");
            // écriture dans le fichier texte
            fputs($f, $text );
            // fermeture
            fclose($f);
            //*********************************************************************************** */

        }else{ 
            $statusMsg = '<p>Sorry, only PDF file is allowed to upload.</p>'; 
        } 
    }else{ 
        $statusMsg = '<p>Please select a PDF file to extract text.</p>'; 
    } 
} 
 
// Display text content 
echo $pdfText;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h2>Extract text from PDF</h2>
        <div class="cw-frm">
            <!-- file upload form -->
            <?php if(!empty($statusMsg)){ ?>
                <div class="status-msg <?php echo $status; ?>"><?php echo $statusMsgM; ?></div>
            <?php } ?>
            <!-- file upload form -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-input">
                    <label for="pdf_file">PDF File</label>
                    <input type="file" name="pdf_file" placeholder="Select a PDF file" required="">
                </div>
                <input type="submit" name="submit" class="btn" value="Extract Text">
            </form>
        </div>
        <div class="wrapper-res">
            <!-- Dispplay text extracted from uploaded pdf -->
            <?php if(!empty($pdfText)){ ?>
                <div class="frm-result">
                    <p><?php echo $pdfText; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    
</body>
</html>